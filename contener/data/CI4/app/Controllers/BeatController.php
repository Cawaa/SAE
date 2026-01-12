<?php

namespace App\Controllers;

use App\Models\BeatModel;
use App\Models\BeatFileModel;
use App\Models\CategoryModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class BeatController extends BaseController
{
    public function index()
    {
        $beatModel = new BeatModel();
        $catModel  = new CategoryModel();

        $beats = $beatModel->getDefaultFeed(24);

        return view('beats/index', [
            'title'      => 'Boutique',
            'beats'      => $beats,
            'categories' => $catModel->orderBy('name', 'ASC')->findAll(),
            'filters'    => [],
            'doSearch'   => false,
        ]);
    }

    public function search()
    {
        $filters = [
            'q'           => $this->request->getGet('q'),
            'category_id' => $this->request->getGet('category_id'),
            'bpm_min'     => $this->request->getGet('bpm_min'),
            'bpm_max'     => $this->request->getGet('bpm_max'),
            'price_min'   => $this->request->getGet('price_min'),
            'price_max'   => $this->request->getGet('price_max'),
            'musical_key' => $this->request->getGet('musical_key'),
            'do_search'   => 1,
        ];

        $beatModel = new BeatModel();
        $catModel  = new CategoryModel();

        $beats = $beatModel->search($filters);

        return view('beats/index', [
            'title'      => 'Recherche',
            'beats'      => $beats,
            'categories' => $catModel->orderBy('name', 'ASC')->findAll(),
            'filters'    => $filters,
            'doSearch'   => true,
        ]);
    }

    public function show(int $id)
    {
        $beatModel = new BeatModel();
        $beat      = $beatModel->getOneWithJoins($id);

        if (!$beat) {
            throw new PageNotFoundException('Beat introuvable.');
        }

        $fileModel   = new BeatFileModel();
        $previewPath = $fileModel->getPreviewPath($id); // ex: uploads/previews/17/xxx.mp3

        return view('beats/show', [
            'title'       => $beat['title'],
            'beat'        => $beat,
            'previewPath' => $previewPath,
        ]);
    }

    public function createForm()
    {
        // sécurité simple si quelqu’un envoie un formulaire énorme
        if ((int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 128 * 1024 * 1024) {
            return redirect()->back()->withInput()->with('error', 'Fichier trop volumineux.');
        }

        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/login');
        }

        $catModel = new CategoryModel();

        return view('beats/form', [
            'title'      => 'Publier un beat',
            'categories' => $catModel->orderBy('name', 'ASC')->findAll(),
            'beat'       => null,
        ]);
    }

    public function create()
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/login');
        }

        $title = trim((string) ($this->request->getPost('title') ?? ''));
        if ($title === '') {
            return redirect()->back()->withInput()->with('error', 'Titre obligatoire.');
        }

        // 1) On exige les 2 fichiers avant de créer quoi que ce soit
        $previewFile = $this->request->getFile('preview_file');
        $masterFile  = $this->request->getFile('original_file');

        if (!$previewFile || !$previewFile->isValid() || $previewFile->hasMoved()) {
            return redirect()->back()->withInput()->with('error', 'Preview MP3 obligatoire.');
        }
        if (!$masterFile || !$masterFile->isValid() || $masterFile->hasMoved()) {
            return redirect()->back()->withInput()->with('error', 'Master WAV obligatoire.');
        }

        // 2) IMPORTANT : capturer MIME/SIZE AVANT move() (sinon /tmp peut disparaître)
        $previewSize = (int) $previewFile->getSize();
        $previewMime = (string) $previewFile->getMimeType();

        $masterSize  = (int) $masterFile->getSize();
        $masterMime  = (string) $masterFile->getMimeType();

        // Validation
        $this->assertUploadValidValues($previewSize, $previewMime, ['audio/mpeg', 'audio/mp3'], 15 * 1024 * 1024, 'preview MP3');
        $this->assertUploadValidValues($masterSize,  $masterMime,  ['audio/wav', 'audio/x-wav'], 80 * 1024 * 1024, 'master WAV');

        $beatData = [
            'user_id'     => $userId,
            'category_id' => (int)($this->request->getPost('category_id') ?? 0) ?: null,
            'bpm'         => $this->request->getPost('bpm') !== null ? (int)$this->request->getPost('bpm') : null,
            'musical_key' => trim((string)($this->request->getPost('musical_key') ?? '')) ?: null,
            'tags'        => trim((string)($this->request->getPost('tags') ?? '')) ?: null,
            'title'       => $title,
            'description' => trim((string)($this->request->getPost('description') ?? '')) ?: null,
            'price'       => (float)($this->request->getPost('price') ?? 0),
            'status'      => 'active',
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        $db        = db_connect();
        $beatModel = new BeatModel();
        $fileModel = new BeatFileModel();

        $createdAbs = [];

        $db->transBegin();

        try {
            // 3) Insert beat -> besoin du beatId pour créer les dossiers
            $beatId = (int) $beatModel->insert($beatData, true);
            if ($beatId <= 0) {
                throw new \RuntimeException('Échec création beat.');
            }

            // 4) Preview -> PUBLIC
            $previewInfo = $this->saveUploadToPublicFile(
                $beatId,
                $previewFile,
                'uploads/previews',
                $previewMime,
                $previewSize,
                $createdAbs
            );

            $fileModel->upsertFile(
                $beatId,
                'preview_mp3',
                $previewInfo['relativePath'],
                $previewInfo['mime'],
                $previewInfo['sizeBytes'],
                $previewInfo['sha256']
            );

            // 5) Master -> WRITABLE
            $masterInfo = $this->saveUploadToWritableFile(
                $beatId,
                $masterFile,
                'uploads/masters',
                $masterMime,
                $masterSize,
                $createdAbs
            );

            $fileModel->upsertFile(
                $beatId,
                'master_wav',
                $masterInfo['relativePath'],
                $masterInfo['mime'],
                $masterInfo['sizeBytes'],
                $masterInfo['sha256']
            );

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaction DB invalide.');
            }

            $db->transCommit();
            return redirect()->to('/my/beats')->with('success', 'Beat publié.');

        } catch (\Throwable $e) {
            $db->transRollback();

            // nettoyage fichiers
            foreach ($createdAbs as $p) {
                if (is_file($p)) {
                    @unlink($p);
                }
            }

            return redirect()->back()->withInput()->with('error', 'Erreur upload/DB : ' . $e->getMessage());
        }
    }

    public function myBeats()
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/login');
        }

        $beatModel = new BeatModel();
        $beats     = $beatModel->findBySeller($userId);

        return view('beats/my', [
            'title' => 'Mes beats',
            'beats' => $beats,
        ]);
    }

    public function download(int $id)
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/login');
        }

        $beatModel = new BeatModel();
        $beat      = $beatModel->find($id);

        if (!$beat) {
            throw new PageNotFoundException('Beat introuvable.');
        }

        if ((int)($beat['buyer_id'] ?? 0) !== $userId) {
            return redirect()->to('/beats/' . $id)->with('error', 'Téléchargement refusé : achat requis.');
        }

        $fileModel = new BeatFileModel();
        $rel       = $fileModel->getMasterPath($id);

        if (!$rel) {
            return redirect()->to('/beats/' . $id)->with('error', 'Fichier WAV introuvable.');
        }

        $rel = str_replace(['..', '\\'], ['', '/'], $rel);
        $abs = rtrim(WRITEPATH, '/\\') . '/' . ltrim($rel, '/');

        if (!is_file($abs)) {
            return redirect()->to('/beats/' . $id)->with('error', 'Fichier WAV manquant sur le serveur.');
        }

        return $this->response->download($abs, null);
    }

    // -------------------
    // Helpers
    // -------------------

    private function assertUploadValidValues(int $sizeBytes, string $mime, array $allowedMimes, int $maxBytes, string $label): void
    {
        if ($sizeBytes <= 0 || $sizeBytes > $maxBytes) {
            throw new \RuntimeException("Fichier trop gros pour {$label} (max " . (int)($maxBytes / 1024 / 1024) . "MB).");
        }

        if (!in_array($mime, $allowedMimes, true)) {
            throw new \RuntimeException("Type invalide pour {$label} ({$mime}).");
        }
    }

    private function ensureDir(string $path): void
    {
        if (is_dir($path)) {
            return;
        }

        if (!@mkdir($path, 0775, true) && !is_dir($path)) {
            throw new \RuntimeException("Impossible de créer le dossier: {$path}");
        }
    }

    /**
     * Stocke un upload dans PUBLIC (FCPATH) : public/uploads/...
     */
    private function saveUploadToPublicFile(
        int $beatId,
        $file,
        string $baseDirRelativeToPublic,
        string $mime,
        int $sizeBytes,
        array &$createdAbs
    ): array {
        $targetDir = rtrim(\FCPATH, '/\\') . '/' . trim($baseDirRelativeToPublic, '/\\') . '/' . $beatId;
        $this->ensureDir($targetDir);

        $newName = $file->getRandomName();
        $file->move($targetDir, $newName, true);

        $abs = rtrim($targetDir, '/\\') . '/' . $newName;
        if (!is_file($abs)) {
            throw new \RuntimeException("Upload échoué (fichier non trouvé après move).");
        }

        $createdAbs[] = $abs;

        $relative = trim($baseDirRelativeToPublic, '/\\') . '/' . $beatId . '/' . $newName;

        return [
            'relativePath' => $relative,
            'mime'         => $mime,
            'sizeBytes'    => $sizeBytes,
            'sha256'       => hash_file('sha256', $abs),
        ];
    }

    /**
     * Stocke un upload dans WRITABLE (WRITEPATH) : writable/uploads/...
     */
    private function saveUploadToWritableFile(
        int $beatId,
        $file,
        string $baseDirRelativeToWritable,
        string $mime,
        int $sizeBytes,
        array &$createdAbs
    ): array {
        $targetDir = rtrim(\WRITEPATH, '/\\') . '/' . trim($baseDirRelativeToWritable, '/\\') . '/' . $beatId;
        $this->ensureDir($targetDir);

        $newName = $file->getRandomName();
        $file->move($targetDir, $newName, true);

        $abs = rtrim($targetDir, '/\\') . '/' . $newName;
        if (!is_file($abs)) {
            throw new \RuntimeException("Upload échoué (fichier non trouvé après move).");
        }

        $createdAbs[] = $abs;

        $relative = trim($baseDirRelativeToWritable, '/\\') . '/' . $beatId . '/' . $newName;

        return [
            'relativePath' => $relative,
            'mime'         => $mime,
            'sizeBytes'    => $sizeBytes,
            'sha256'       => hash_file('sha256', $abs),
        ];
    }
}
