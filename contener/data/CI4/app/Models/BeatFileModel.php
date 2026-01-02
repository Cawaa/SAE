<?php

namespace App\Models;

use CodeIgniter\Model;

class BeatFileModel extends Model
{
    protected $table      = 'beat_files';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'beat_id','type','path','mime_type','size_bytes','duration_sec','created_at'
    ];

    protected $useTimestamps = false;

    public function getPreviewPath(int $beatId): ?string
    {
        $row = $this->where('beat_id', $beatId)
            ->where('type', 'preview_mp3')
            ->first();

        return $row ? $row['path'] : null;
    }
}
