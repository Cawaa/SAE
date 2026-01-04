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
                'status' => 'sold',
                'buyer_id' => 3,
                'sold_at' => $now,
                'is_featured' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // --- NOUVEAUX BEATS AJOUTÉS ---

            // Drill (User 2)
            [
                'user_id' => 2,
                'category_id' => 2, // Trap/Drill
                'bpm' => 142,
                'musical_key' => 'Cm',
                'tags' => 'drill,uk,sliding-808',
                'title' => 'London Fog',
                'description' => 'Drill UK percutante avec des basses glissantes.',
                'price' => 55.00,
                'status' => 'active',
                'buyer_id' => null,
                'sold_at' => null,
                'is_featured' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // R&B (User 3)
            [
                'user_id' => 3,
                'category_id' => 3, // R&B (Assumons ID 3)
                'bpm' => 110,
                'musical_key' => 'Bb',
                'tags' => 'rnb,smooth,love',
                'title' => 'Late Night Call',
                'description' => 'Ambiance R&B moderne style The Weeknd.',
                'price' => 45.00,
                'status' => 'active',
                'buyer_id' => null,
                'sold_at' => null,
                'is_featured' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Afrobeat (User 2)
            [
                'user_id' => 2,
                'category_id' => 4, // Afrobeat (Assumons ID 4)
                'bpm' => 100,
                'musical_key' => 'Gmaj',
                'tags' => 'afro,dancehall,summer',
                'title' => 'Lagos Vibes',
                'description' => 'Rythmes afro entraînants pour l\'été.',
                'price' => 40.00,
                'status' => 'active',
                'buyer_id' => null,
                'sold_at' => null,
                'is_featured' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Synthwave (User 3)
            [
                'user_id' => 3,
                'category_id' => 5, // Electro/Synth
                'bpm' => 120,
                'musical_key' => 'Dm',
                'tags' => 'retro,80s,synth',
                'title' => 'Neon Highway',
                'description' => 'Synthwave rétro futuriste style années 80.',
                'price' => 35.00,
                'status' => 'active',
                'buyer_id' => null,
                'sold_at' => null,
                'is_featured' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Pop (User 2)
            [
                'user_id' => 2,
                'category_id' => 6, // Pop
                'bpm' => 128,
                'musical_key' => 'Emaj',
                'tags' => 'pop,radio,happy',
                'title' => 'Sunny Day',
                'description' => 'Pop commerciale calibrée pour la radio.',
                'price' => 60.00,
                'status' => 'active',
                'buyer_id' => null,
                'sold_at' => null,
                'is_featured' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Grime (User 3)
            [
                'user_id' => 3,
                'category_id' => 1, // Hip-Hop
                'bpm' => 140,
                'musical_key' => 'Fm',
                'tags' => 'grime,fast,aggressive',
                'title' => 'East End',
                'description' => 'Grime rapide et agressif.',
                'price' => 30.00,
                'status' => 'active',
                'buyer_id' => null,
                'sold_at' => null,
                'is_featured' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Trap Mélodique (User 2)
            [
                'user_id' => 2,
                'category_id' => 2, // Trap
                'bpm' => 135,
                'musical_key' => 'Bm',
                'tags' => 'melodic,guitar,emo',
                'title' => 'Broken Strings',
                'description' => 'Trap mélodique avec guitare acoustique.',
                'price' => 50.00,
                'status' => 'active',
                'buyer_id' => null,
                'sold_at' => null,
                'is_featured' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Old School (User 3)
            [
                'user_id' => 3,
                'category_id' => 1, // Hip-Hop
                'bpm' => 95,
                'musical_key' => 'Gm',
                'tags' => '90s,ny,jazz-rap',
                'title' => 'Queensbridge',
                'description' => 'Hommage au son New-Yorkais des années 90.',
                'price' => 42.00,
                'status' => 'active',
                'buyer_id' => null,
                'sold_at' => null,
                'is_featured' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $this->db->table('beats')->insertBatch($beats);
    }
}

