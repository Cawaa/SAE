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
        $catModel = new CategoryModel();
        $categories = $catModel->orderBy('name', 'ASC')->findAll();

        $beatModel = new BeatModel();

        $filters = [
            'q'           => $this->request->getGet('q'),
            'category_id' => $this->request->getGet('category_id'),
            'bpm_min'     => $this->request->getGet('bpm_min'),
            'bpm_max'     => $this->request->getGet('bpm_max'),
            'musical_key' => $this->request->getGet('musical_key'),
        ];

        $doSearch = (string)($this->request->getGet('do_search') ?? '') === '1';

        if ($doSearch) {
            $beats = $beatModel->search($filters);
        } else {
            $beats = $beatModel->getDefaultFeed(30);
        }

        return view('beats/index', [
            'title'      => 'Boutique',
            'beats'      => $beats,
            'filters'    => $filters,
            'categories' => $categories,
            'doSearch'   => $doSearch,
        ]);
    }

    public function search()
    {
        return $this->index();
    }

    public function show(int $id)
    {
        $beatModel = new BeatModel();
        $beat = $beatModel->getOneWithJoins($id);

        if (!$beat) {
            throw new PageNotFoundException('Beat introuvable.');
        }

        $fileModel = new BeatFileModel();
        $previewPath = $fileModel->getPreviewPath($id);

        // ✅ FIX: pas de $this->db dans ce controller -> db_connect()
        $db = db_connect();

        $suggestions = $db->table('beats b')
            ->select('b.id, b.title, b.price, b.bpm, b.musical_key')
            ->where('b.user_id', (int)$beat['user_id'])
            ->where('b.id !=', (int)$id)
            ->where('b.status', 'active')
            ->where('b.buyer_id', null)
            ->orderBy('b.created_at', 'DESC')
            ->limit(6)
            ->get()
            ->getResultArray();

        return view('beats/show', [
            'title'       => $beat['title'],
            'beat'        => $beat,
            'previewPath' => $previewPath,
            'suggestions' => $suggestions,
        ]);
    }

    public function myBeats()
    {
        $userId = (int)(session()->get('user_id') ?? 0);
        if ($userId <= 0) return redirect()->to('/login');

        $beatModel = new BeatModel();
        $beats = $beatModel->findBySeller($userId);

        return view('beats/my', [
            'title' => 'Mes beats',
            'beats' => $beats,
        ]);
    }

    public function createForm()
    {
        $userId = (int)(session()->get('user_id') ?? 0);
        if ($userId <= 0) return redirect()->to('/login');

        $catModel = new CategoryModel();

        return view('beats/form', [
            'title'      => 'Publier un beat',
            'categories' => $catModel->orderBy('name', 'ASC')->findAll(),
            'beat'       => null,
        ]);
    }

    public function create()
    {
        $userId = (int)(session()->get('user_id') ?? 0);
        if ($userId <= 0) return redirect()->to('/login');

        $data = [
            'user_id'     => $userId,
            'category_id' => (int)($this->request->getPost('category_id') ?? 0) ?: null,
            'bpm'         => $this->request->getPost('bpm') !== null ? (int)$this->request->getPost('bpm') : null,
            'musical_key' => trim((string)($this->request->getPost('musical_key') ?? '')) ?: null,
            'tags'        => trim((string)($this->request->getPost('tags') ?? '')) ?: null,
            'title'       => trim((string)($this->request->getPost('title') ?? '')),
            'description' => trim((string)($this->request->getPost('description') ?? '')) ?: null,
            'price'       => (float)($this->request->getPost('price') ?? 0),
            'status'      => 'active',
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        if ($data['title'] === '') {
            return redirect()->back()->withInput()->with('error', 'Titre obligatoire.');
        }

        $beatModel = new BeatModel();
        $beatModel->insert($data);
        $beatId = (int)$beatModel->getInsertID();

        $fileModel = new BeatFileModel();

        try {
            $preview = $this->saveBeatUpload(
                $beatId,
                'preview_file',
                'preview',
                ['audio/mpeg'],
                5 * 1024 * 1024
            );

            if ($preview !== null) {
                $this->upsertBeatFile($fileModel, $beatId, 'preview_mp3', $preview['path'], $preview['mime'], (int)$preview['size']);
            }

            $original = $this->saveBeatUpload(
                $beatId,
                'original_file',
                'original',
                ['audio/mpeg', 'audio/wav'],
                25 * 1024 * 1024
            );

            if ($original !== null) {
                $type = ($original['mime'] === 'audio/wav') ? 'original_wav' : 'original_mp3';
                $this->upsertBeatFile($fileModel, $beatId, $type, $original['path'], $original['mime'], (int)$original['size']);
            }
        } catch (\Throwable $e) {
            return redirect()->to('/my/beats')->with('error', 'Beat créé mais upload échoué : ' . $e->getMessage());
        }

        return redirect()->to('/my/beats')->with('success', 'Beat publié.');
    }

    public function editForm(int $id)
    {
        $userId = (int)(session()->get('user_id') ?? 0);
        if ($userId <= 0) return redirect()->to('/login');

        $beatModel = new BeatModel();
        $beat = $beatModel->find($id);
        if (!$beat) throw new PageNotFoundException('Beat introuvable.');

        if ((int)$beat['user_id'] !== $userId) {
            return redirect()->to('/my/beats')->with('error', 'Accès refusé.');
        }

        if (!empty($beat['buyer_id']) || ($beat['status'] ?? '') !== 'active') {
            return redirect()->to('/my/beats')->with('error', 'Beat vendu ou inactif, modification impossible.');
        }

        $catModel = new CategoryModel();

        return view('beats/form', [
            'title'      => 'Modifier un beat',
            'beat'       => $beat,
            'categories' => $catModel->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    public function update(int $id)
    {
        $userId = (int)(session()->get('user_id') ?? 0);
        if ($userId <= 0) return redirect()->to('/login');

        $beatModel = new BeatModel();
        $beat = $beatModel->find($id);
        if (!$beat) throw new PageNotFoundException('Beat introuvable.');

        if ((int)$beat['user_id'] !== $userId) {
            return redirect()->to('/my/beats')->with('error', 'Accès refusé.');
        }

        if (!empty($beat['buyer_id']) || ($beat['status'] ?? '') !== 'active') {
            return redirect()->to('/my/beats')->with('error', 'Beat vendu ou inactif, modification impossible.');
        }

        $data = [
            'category_id' => (int)($this->request->getPost('category_id') ?? 0) ?: null,
            'bpm'         => $this->request->getPost('bpm') !== null ? (int)$this->request->getPost('bpm') : null,
            'musical_key' => trim((string)($this->request->getPost('musical_key') ?? '')) ?: null,
            'tags'        => trim((string)($this->request->getPost('tags') ?? '')) ?: null,
            'title'       => trim((string)($this->request->getPost('title') ?? '')),
            'description' => trim((string)($this->request->getPost('description') ?? '')) ?: null,
            'price'       => (float)($this->request->getPost('price') ?? 0),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        if ($data['title'] === '') {
            return redirect()->back()->withInput()->with('error', 'Titre obligatoire.');
        }

        $beatModel->update($id, $data);

        $fileModel = new BeatFileModel();

        try {
            $preview = $this->saveBeatUpload(
                $id,
                'preview_file',
                'preview',
                ['audio/mpeg'],
                5 * 1024 * 1024
            );

            if ($preview !== null) {
                $this->upsertBeatFile($fileModel, $id, 'preview_mp3', $preview['path'], $preview['mime'], (int)$preview['size']);
            }

            $original = $this->saveBeatUpload(
                $id,
                'original_file',
                'original',
                ['audio/mpeg', 'audio/wav'],
                25 * 1024 * 1024
            );

            if ($original !== null) {
                $type = ($original['mime'] === 'audio/wav') ? 'original_wav' : 'original_mp3';
                $this->upsertBeatFile($fileModel, $id, $type, $original['path'], $original['mime'], (int)$original['size']);
            }
        } catch (\Throwable $e) {
            return redirect()->to('/my/beats')->with('error', 'Beat modifié mais upload échoué : ' . $e->getMessage());
        }

        return redirect()->to('/my/beats')->with('success', 'Beat mis à jour.');
    }

    public function delete(int $id)
    {
        $userId = (int)(session()->get('user_id') ?? 0);
        if ($userId <= 0) return redirect()->to('/login');

        $beatModel = new BeatModel();
        $beat = $beatModel->find($id);
        if (!$beat) throw new PageNotFoundException('Beat introuvable.');

        if ((int)$beat['user_id'] !== $userId) {
            return redirect()->to('/my/beats')->with('error', 'Accès refusé.');
        }

        $beatModel->update($id, [
            'status' => 'deleted',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/my/beats')->with('success', 'Beat supprimé.');
    }

    private function saveBeatUpload(int $beatId, string $inputName, string $kind, array $allowedMime, int $maxBytes): ?array
    {
        $file = $this->request->getFile($inputName);

        if (!$file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (!$file->isValid()) {
            throw new \RuntimeException("Upload invalide pour $inputName.");
        }

        if ($file->getSize() > $maxBytes) {
            throw new \RuntimeException("Fichier trop lourd pour $inputName.");
        }

        $mime = $file->getClientMimeType();
        if (!in_array($mime, $allowedMime, true)) {
            throw new \RuntimeException("Type de fichier non autorisé pour $inputName.");
        }

        $dir = FCPATH . 'uploads/beats/' . $beatId;
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $ext = strtolower($file->getExtension());
        $filename = ($kind === 'preview') ? "preview.$ext" : "original.$ext";

        $file->move($dir, $filename, true);

        return [
            'path' => 'uploads/beats/' . $beatId . '/' . $filename,
            'mime' => $mime,
            'size' => $file->getSize(),
        ];
    }

    private function upsertBeatFile(BeatFileModel $fileModel, int $beatId, string $type, string $path, string $mime, int $sizeBytes): void
    {
        $now = date('Y-m-d H:i:s');

        $row = $fileModel->where('beat_id', $beatId)
            ->where('type', $type)
            ->first();

        $payload = [
            'beat_id' => $beatId,
            'type' => $type,
            'path' => $path,
            'mime_type' => $mime,
            'size_bytes' => $sizeBytes,
            'duration_sec' => null,
            'created_at' => $now,
        ];

        if ($row) {
            unset($payload['created_at']);
            $fileModel->update((int)$row['id'], $payload);
        } else {
            $fileModel->insert($payload);
        }
    }
}
