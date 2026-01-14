<?php

namespace App\Controllers;

use App\Models\BeatModel;
use App\Models\BeatFileModel;
use App\Models\CategoryModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use App\Decorators\BaseBeat;
use App\Decorators\PromoDecorator;

/*
 * Contrôleur pour la gestion des beats (affichage, création, édition, téléchargement).
 */
class BeatController extends BaseController
{
    // Affiche la liste des beats disponibles.
    public function index()
    {
        $beatModel = new BeatModel();
        $catModel  = new CategoryModel();

        // Récupère les beats avec décorateurs
        $rawBeats = $beatModel->getDefaultFeed(24);
        $decoratedBeats = $this->decorateBeats($rawBeats); // Transformation ici

        return view('beats/index', [
            'title'      => 'Boutique',
            'beats'      => $decoratedBeats,
            'categories' => $catModel->orderBy('name', 'ASC')->findAll(),
            'filters'    => [],
            'doSearch'   => false,
        ]);
    }

    // Recherche de beats avec filtres.
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

        $rawBeats = $beatModel->search($filters);
        $decoratedBeats = $this->decorateBeats($rawBeats); // Transformation ici

        return view('beats/index', [
            'title'      => 'Recherche',
            'beats'      => $decoratedBeats,
            'categories' => $catModel->orderBy('name', 'ASC')->findAll(),
            'filters'    => $filters,
            'doSearch'   => true,
        ]);
    }
    /**
     * Helper interne pour appliquer les décorateurs
     */
    private function decorateBeats(array $beats): array
    {
        $result = [];
        foreach ($beats as $data) {
            $beat = new BaseBeat($data);

            // Applique le décorateur de promotion si le prix est supérieur à 50
            if ($beat->getPrice() > 50) {
                $beat = new PromoDecorator($beat);
            }

            $result[] = $beat;
        }
        return $result;
    }

    // Affiche les détails d'un beat spécifique.
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

    // Affiche le formulaire de création d'un nouveau beat.
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

    // Traite la création d'un nouveau beat.
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

    /**
     * GET /beats/{id}/edit
     * Affiche le formulaire d'édition (propriétaire uniquement, non vendu).
     */
    public function editForm(int $id)
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

        // Ownership
        if ((int)($beat['user_id'] ?? 0) !== $userId) {
            return redirect()->to('/my/beats')->with('error', 'Accès refusé : vous ne pouvez modifier que vos beats.');
        }

        // Refuser édition si vendu / non actif
        $isSold = !empty($beat['buyer_id']);
        $isActive = (($beat['status'] ?? '') === 'active');

        if ($isSold || !$isActive) {
            return redirect()->to('/my/beats')->with('error', 'Beat vendu, modification impossible.');
        }

        $catModel = new CategoryModel();

        return view('beats/form', [
            'title'      => 'Modifier un beat',
            'categories' => $catModel->orderBy('name', 'ASC')->findAll(),
            'beat'       => $beat,
        ]);
    }

    /**
     * POST /beats/{id}/edit
     * Traite la mise à jour (propriétaire uniquement, fichiers optionnels).
     */
    public function update(int $id)
    {
        $userId = (int) (session()->get('user_id') ?? 0);
        if ($userId <= 0) {
            return redirect()->to('/login');
        }

        $beatModel = new BeatModel();
        $fileModel = new BeatFileModel();

        $beat = $beatModel->find($id);
        if (!$beat) {
            throw new PageNotFoundException('Beat introuvable.');
        }

        // Ownership
        if ((int)($beat['user_id'] ?? 0) !== $userId) {
            return redirect()->to('/my/beats')->with('error', 'Accès refusé : vous ne pouvez modifier que vos beats.');
        }

        // Refuser édition si vendu / non actif
        $isSold = !empty($beat['buyer_id']);
        $isActive = (($beat['status'] ?? '') === 'active');

        if ($isSold || !$isActive) {
            return redirect()->to('/my/beats')->with('error', 'Beat vendu, modification impossible.');
        }

        $title = trim((string) ($this->request->getPost('title') ?? ''));
        if ($title === '') {
            return redirect()->back()->withInput()->with('error', 'Titre obligatoire.');
        }

        $updateData = [
            'category_id' => (int)($this->request->getPost('category_id') ?? 0) ?: null,
            'bpm'         => $this->request->getPost('bpm') !== null ? (int)$this->request->getPost('bpm') : null,
            'musical_key' => trim((string)($this->request->getPost('musical_key') ?? '')) ?: null,
            'tags'        => trim((string)($this->request->getPost('tags') ?? '')) ?: null,
            'title'       => $title,
            'description' => trim((string)($this->request->getPost('description') ?? '')) ?: null,
            'price'       => (float)($this->request->getPost('price') ?? 0),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        $previewFile = $this->request->getFile('preview_file');
        $masterFile  = $this->request->getFile('original_file');

        // Pour rollback si erreur
        $createdAbs = [];
        // Pour suppression après commit (évite perdre l’ancien si rollback)
        $deleteAfterCommitAbs = [];

        $db = db_connect();
        $db->transBegin();

        try {
            // 1) Update DB beat
            if ($beatModel->update($id, $updateData) === false) {
                throw new \RuntimeException('Échec mise à jour beat.');
            }

            // 2) Preview optionnelle (PUBLIC)
            if ($previewFile && $previewFile->isValid() && !$previewFile->hasMoved() && $previewFile->getSize() > 0) {
                $oldRel = $fileModel->getPreviewPath($id);

                $previewSize = (int) $previewFile->getSize();
                $previewMime = (string) $previewFile->getMimeType();

                $this->assertUploadValidValues($previewSize, $previewMime, ['audio/mpeg', 'audio/mp3'], 15 * 1024 * 1024, 'preview MP3');

                $previewInfo = $this->saveUploadToPublicFile(
                    $id,
                    $previewFile,
                    'uploads/previews',
                    $previewMime,
                    $previewSize,
                    $createdAbs
                );

                $fileModel->upsertFile(
                    $id,
                    'preview_mp3',
                    $previewInfo['relativePath'],
                    $previewInfo['mime'],
                    $previewInfo['sizeBytes'],
                    $previewInfo['sha256']
                );

                // Nettoyage ancien fichier (après commit)
                if ($oldRel) {
                    $oldRel = str_replace(['..', '\\'], ['', '/'], (string)$oldRel);
                    $oldAbs = rtrim(\FCPATH, '/\\') . '/' . ltrim($oldRel, '/');
                    if (is_file($oldAbs)) {
                        $deleteAfterCommitAbs[] = $oldAbs;
                    }
                }
            }

            // 3) Master optionnel (WRITABLE)
            if ($masterFile && $masterFile->isValid() && !$masterFile->hasMoved() && $masterFile->getSize() > 0) {
                $oldRel = $fileModel->getMasterPath($id);

                $masterSize = (int) $masterFile->getSize();
                $masterMime = (string) $masterFile->getMimeType();

                $this->assertUploadValidValues($masterSize, $masterMime, ['audio/wav', 'audio/x-wav'], 80 * 1024 * 1024, 'master WAV');

                $masterInfo = $this->saveUploadToWritableFile(
                    $id,
                    $masterFile,
                    'uploads/masters',
                    $masterMime,
                    $masterSize,
                    $createdAbs
                );

                $fileModel->upsertFile(
                    $id,
                    'master_wav',
                    $masterInfo['relativePath'],
                    $masterInfo['mime'],
                    $masterInfo['sizeBytes'],
                    $masterInfo['sha256']
                );

                // Nettoyage ancien fichier (après commit)
                if ($oldRel) {
                    $oldRel = str_replace(['..', '\\'], ['', '/'], (string)$oldRel);
                    $oldAbs = rtrim(\WRITEPATH, '/\\') . '/' . ltrim($oldRel, '/');
                    if (is_file($oldAbs)) {
                        $deleteAfterCommitAbs[] = $oldAbs;
                    }
                }
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaction DB invalide.');
            }

            $db->transCommit();

            // Suppression des anciens fichiers si remplacement (après commit)
            foreach ($deleteAfterCommitAbs as $p) {
                if (is_file($p)) {
                    @unlink($p);
                }
            }

            return redirect()->to('/my/beats')->with('success', 'Beat modifié.');

        } catch (\Throwable $e) {
            $db->transRollback();

            // Nettoyage des nouveaux fichiers si rollback
            foreach ($createdAbs as $p) {
                if (is_file($p)) {
                    @unlink($p);
                }
            }

            return redirect()->back()->withInput()->with('error', 'Erreur mise à jour : ' . $e->getMessage());
        }
    }

    // Affiche les beats du vendeur connecté.
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

    // Permet le téléchargement d'un beat acheté.
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
