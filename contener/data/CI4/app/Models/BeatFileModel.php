<?php

namespace App\Models;

use CodeIgniter\Model;

// Modèle pour gérer les fichiers associés aux beats.
class BeatFileModel extends Model
{
    protected $table      = 'beat_files';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'beat_id',
        'type',
        'path',
        'mime_type',
        'size_bytes',
        'sha256',
        'duration_sec',
        'created_at',
    ];

    protected $useTimestamps = false;

    // Récupère le chemin du fichier pour un beat et un type donnés.
    public function getPathByType(int $beatId, string $type): ?string
    {
        $row = $this->where('beat_id', $beatId)
            ->where('type', $type)
            ->first();

        return $row['path'] ?? null;
    }

    /**
     * IMPORTANT : désormais c'est un chemin PUBLIC relatif
     * ex: uploads/previews/17/xxx.mp3
     */
    public function getPreviewPath(int $beatId): ?string
    {
        return $this->getPathByType($beatId, 'preview_mp3');
    }

    /**
     * Chemin WRITABLE relatif
     * ex: uploads/masters/17/xxx.wav
     */
    public function getMasterPath(int $beatId): ?string
    {
        return $this->getPathByType($beatId, 'master_wav');
    }

    // Insère ou met à jour un fichier de beat.
    public function upsertFile(
        int $beatId,
        string $type,
        string $path,
        string $mime,
        int $sizeBytes,
        ?string $sha256 = null,
        ?int $durationSec = null
    ): void {
        $existing = $this->where('beat_id', $beatId)
            ->where('type', $type)
            ->first();

        $data = [
            'beat_id'       => $beatId,
            'type'          => $type,
            'path'          => $path,
            'mime_type'     => $mime,
            'size_bytes'    => $sizeBytes,
            'sha256'        => $sha256,
            'duration_sec'  => $durationSec,
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        if ($existing) {
            $this->update((int) $existing['id'], $data);
        } else {
            $this->insert($data);
        }
    }
}
