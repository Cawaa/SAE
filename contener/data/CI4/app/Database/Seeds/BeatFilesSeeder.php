<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BeatFilesSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        if ($this->db->table('beat_files')->countAllResults() > 0) {
            return;
        }

        // Les chemins sont fictifs pour dev/test (à toi de mettre les vrais après upload)
        $files = [
            // beat 1
            [
                'beat_id' => 1,
                'type' => 'preview_mp3',
                'path' => 'uploads/beats/1/preview.mp3',
                'mime_type' => 'audio/mpeg',
                'size_bytes' => 1200000,
                'duration_sec' => 120,
                'created_at' => $now,
            ],
            [
                'beat_id' => 1,
                'type' => 'original_wav',
                'path' => 'uploads/beats/1/original.wav',
                'mime_type' => 'audio/wav',
                'size_bytes' => 25000000,
                'duration_sec' => 180,
                'created_at' => $now,
            ],

            // beat 2
            [
                'beat_id' => 2,
                'type' => 'preview_mp3',
                'path' => 'uploads/beats/2/preview.mp3',
                'mime_type' => 'audio/mpeg',
                'size_bytes' => 1100000,
                'duration_sec' => 130,
                'created_at' => $now,
            ],
            [
                'beat_id' => 2,
                'type' => 'original_mp3',
                'path' => 'uploads/beats/2/original.mp3',
                'mime_type' => 'audio/mpeg',
                'size_bytes' => 3500000,
                'duration_sec' => 200,
                'created_at' => $now,
            ],

            // beat 3 (vendu)
            [
                'beat_id' => 3,
                'type' => 'preview_mp3',
                'path' => 'uploads/beats/3/preview.mp3',
                'mime_type' => 'audio/mpeg',
                'size_bytes' => 900000,
                'duration_sec' => 90,
                'created_at' => $now,
            ],
            [
                'beat_id' => 3,
                'type' => 'original_wav',
                'path' => 'uploads/beats/3/original.wav',
                'mime_type' => 'audio/wav',
                'size_bytes' => 22000000,
                'duration_sec' => 190,
                'created_at' => $now,
            ],
        ];

        $this->db->table('beat_files')->insertBatch($files);
    }
}
