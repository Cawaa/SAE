<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BeatFilesSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // Si déjà seedé, on ne refait rien
        if ($this->db->table('beat_files')->countAllResults() > 0) {
            return;
        }

        // 1) Fichiers source (dans app/Database/Seeds/assets/)
        $assetsDir  = rtrim(APPPATH, '/\\') . '/Database/Seeds/assets';
        $srcPreview = $assetsDir . '/preview.mp3';
        $srcMaster  = $assetsDir . '/master.wav';

        if (!is_file($srcPreview)) {
            throw new \RuntimeException(
                "BeatFilesSeeder: fichier manquant: {$srcPreview}. " .
                "Ajoute un MP3 réel dans app/Database/Seeds/assets/preview.mp3"
            );
        }
        if (!is_file($srcMaster)) {
            throw new \RuntimeException(
                "BeatFilesSeeder: fichier manquant: {$srcMaster}. " .
                "Ajoute un WAV réel dans app/Database/Seeds/assets/master.wav"
            );
        }

        // Helper mkdir
        $ensureDir = static function (string $dir): void {
            if (is_dir($dir)) return;
            if (!@mkdir($dir, 0775, true) && !is_dir($dir)) {
                throw new \RuntimeException("Impossible de créer le dossier: {$dir}");
            }
        };

        // 2) Récupère tous les beats existants
        $beatIds = array_map(
            static fn ($r) => (int) $r['id'],
            $this->db->table('beats')->select('id')->get()->getResultArray()
        );

        if (empty($beatIds)) {
            return;
        }

        $rows = [];

        foreach ($beatIds as $beatId) {

            // -----------------------------
            // PREVIEW (public)
            // -----------------------------
            $previewPublicDir = rtrim(FCPATH, '/\\') . '/uploads/previews/' . $beatId;
            $ensureDir($previewPublicDir);

            $previewAbs = $previewPublicDir . '/preview.mp3';

            // Copie seulement si absent
            if (!is_file($previewAbs)) {
                if (!@copy($srcPreview, $previewAbs)) {
                    throw new \RuntimeException("Impossible de copier la preview vers {$previewAbs}");
                }
            }

            $previewRel  = 'uploads/previews/' . $beatId . '/preview.mp3';
            $previewSize = is_file($previewAbs) ? (int) filesize($previewAbs) : 0;
            $previewSha  = is_file($previewAbs) ? (@hash_file('sha256', $previewAbs) ?: null) : null;

            $rows[] = [
                'beat_id'      => $beatId,
                'type'         => 'preview_mp3',
                'path'         => $previewRel,      // relatif à public/
                'mime_type'    => 'audio/mpeg',
                'size_bytes'   => $previewSize,
                'sha256'       => $previewSha,
                'duration_sec' => null,
                'created_at'   => $now,
            ];

            // -----------------------------
            // MASTER (writable)
            // -----------------------------
            $masterWritableDir = rtrim(WRITEPATH, '/\\') . '/uploads/masters/' . $beatId;
            $ensureDir($masterWritableDir);

            $masterAbs = $masterWritableDir . '/master.wav';

            if (!is_file($masterAbs)) {
                if (!@copy($srcMaster, $masterAbs)) {
                    throw new \RuntimeException("Impossible de copier le master vers {$masterAbs}");
                }
            }

            $masterRel  = 'uploads/masters/' . $beatId . '/master.wav';
            $masterSize = is_file($masterAbs) ? (int) filesize($masterAbs) : 0;
            $masterSha  = is_file($masterAbs) ? (@hash_file('sha256', $masterAbs) ?: null) : null;

            $rows[] = [
                'beat_id'      => $beatId,
                'type'         => 'master_wav',
                'path'         => $masterRel,       // relatif à writable/
                'mime_type'    => 'audio/wav',
                'size_bytes'   => $masterSize,
                'sha256'       => $masterSha,
                'duration_sec' => null,
                'created_at'   => $now,
            ];
        }

        $this->db->table('beat_files')->insertBatch($rows);
    }
}
