<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BeatsSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        if ($this->db->table('beats')->countAllResults() > 0) {
            return;
        }

        // Hypothèse : alice=2, bob=3
        $beats = [
            [
                'user_id' => 2,
                'category_id' => 2, // Trap
                'bpm' => 140,
                'musical_key' => 'Am',
                'tags' => 'dark,808,aggressive',
                'title' => 'Midnight 808',
                'description' => 'Trap sombre, grosse 808, idéal pour topline.',
                'price' => 49.99,
                'status' => 'active',
                'buyer_id' => null,
                'sold_at' => null,
                'is_featured' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => 3,
                'category_id' => 1, // Hip-Hop
                'bpm' => 92,
                'musical_key' => 'F#m',
                'tags' => 'boom-bap,vinyl,chill',
                'title' => 'Dusty Crate',
                'description' => 'Boom bap old school, vibe vinyle.',
                'price' => 39.00,
                'status' => 'active',
                'buyer_id' => null,
                'sold_at' => null,
                'is_featured' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => 2,
                'category_id' => 9, // Lo-Fi
                'bpm' => 78,
                'musical_key' => 'Cmaj',
                'tags' => 'lofi,study,soft',
                'title' => 'Coffee Break',
                'description' => 'Lo-fi doux, parfait pour une ambiance chill.',
                'price' => 25.00,
                // Exemple beat déjà vendu (exclusif)
                'status' => 'sold',
                'buyer_id' => 3,     // acheté par bob
                'sold_at' => $now,
                'is_featured' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $this->db->table('beats')->insertBatch($beats);
    }
}
