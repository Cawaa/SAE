<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BeatsSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // On évite d’insérer deux fois
        if ($this->db->table('beats')->countAllResults() > 0) {
            return;
        }

        /**
         * Hypothèses simples et stables (TD) :
         * - user_id 2 = vendeur principal
         * - user_id 3 = autre vendeur / acheteur pour beat vendu
         * - category_id 1 existe (si pas sûr: mettre null)
         */
        $defaultCategoryId = 1;

        $beats = [
            // ----- Tes 3 beats de base -----
            [
                'user_id'      => 2,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 140,
                'musical_key'  => 'Am',
                'tags'         => 'trap,dark,808',
                'title'        => 'Dark Trap Beat',
                'description'  => 'Beat trap sombre, parfait pour une topline agressive.',
                'price'        => 19.99,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'user_id'      => 4,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 95,
                'musical_key'  => 'C#m',
                'tags'         => 'lofi,chill,study',
                'title'        => 'Lo-Fi Chill Beat',
                'description'  => 'Ambiance lo-fi, douce et relax.',
                'price'        => 14.50,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                // Exemple beat déjà vendu
                'user_id'      => 2,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 128,
                'musical_key'  => 'Gm',
                'tags'         => 'club,energy,groove',
                'title'        => 'Club Anthem Beat',
                'description'  => 'Beat énergique, vibes club.',
                'price'        => 25.00,
                'status'       => 'sold',
                'buyer_id'     => 3,
                'sold_at'      => $now,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Midnight 808 (Trap)
            [
                'user_id'      => 2,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 140,
                'musical_key'  => 'Am',
                'tags'         => 'dark,808,aggressive',
                'title'        => 'Midnight 808',
                'description'  => 'Trap sombre, grosse 808, idéal pour topline.',
                'price'        => 49.99,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Dusty Crate (Boom-bap)
            [
                'user_id'      => 3,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 92,
                'musical_key'  => 'F#m',
                'tags'         => 'boom-bap,vinyl,chill',
                'title'        => 'Dusty Crate',
                'description'  => 'Boom bap old school, vibe vinyle.',
                'price'        => 39.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Coffee Break (Lo-fi) - vendu
            [
                'user_id'      => 2,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 78,
                'musical_key'  => 'Cmaj',
                'tags'         => 'lofi,study,soft',
                'title'        => 'Coffee Break',
                'description'  => 'Lo-fi doux, parfait pour une ambiance chill.',
                'price'        => 25.00,
                'status'       => 'sold',
                'buyer_id'     => 3,
                'sold_at'      => $now,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Drill
            [
                'user_id'      => 2,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 142,
                'musical_key'  => 'Cm',
                'tags'         => 'drill,uk,sliding-808',
                'title'        => 'London Fog',
                'description'  => 'Drill UK percutante avec des basses glissantes.',
                'price'        => 55.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // R&B
            [
                'user_id'      => 3,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 110,
                'musical_key'  => 'Bb',
                'tags'         => 'rnb,smooth,love',
                'title'        => 'Late Night Call',
                'description'  => 'Ambiance R&B moderne (vibe The Weeknd).',
                'price'        => 45.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Afrobeat
            [
                'user_id'      => 2,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 100,
                'musical_key'  => 'Gmaj',
                'tags'         => 'afro,dancehall,summer',
                'title'        => 'Lagos Vibes',
                'description'  => 'Rythmes afro entraînants pour l\'été.',
                'price'        => 40.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Synthwave
            [
                'user_id'      => 3,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 120,
                'musical_key'  => 'Dm',
                'tags'         => 'retro,80s,synth',
                'title'        => 'Neon Highway',
                'description'  => 'Synthwave rétro-futuriste style années 80.',
                'price'        => 35.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Pop
            [
                'user_id'      => 4,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 128,
                'musical_key'  => 'Emaj',
                'tags'         => 'pop,radio,happy',
                'title'        => 'Sunny Day',
                'description'  => 'Pop commerciale calibrée pour la radio.',
                'price'        => 60.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Grime
            [
                'user_id'      => 4,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 140,
                'musical_key'  => 'Fm',
                'tags'         => 'grime,fast,aggressive',
                'title'        => 'East End',
                'description'  => 'Grime rapide et agressif.',
                'price'        => 30.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Trap mélodique
            [
                'user_id'      => 5,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 135,
                'musical_key'  => 'Bm',
                'tags'         => 'melodic,guitar,emo',
                'title'        => 'Broken Strings',
                'description'  => 'Trap mélodique avec guitare acoustique.',
                'price'        => 50.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Old school 90s
            [
                'user_id'      => 5,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 95,
                'musical_key'  => 'Gm',
                'tags'         => '90s,ny,jazz-rap',
                'title'        => 'Queensbridge',
                'description'  => 'Hommage au son New-Yorkais des années 90.',
                'price'        => 42.00,
                'status'       => 'sold',
                'buyer_id'     => 4,
                'sold_at'      => $now,
                'is_featured'  => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Brooklyn Bridge
            [
                'user_id'      => 6,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 90,
                'musical_key'  => 'Gm',
                'tags'         => 'boombap,old school,vinyl',
                'title'        => 'Brooklyn Bridge',
                'description'  => 'Boom Bap classique avec textures vinyle.',
                'price'        => 40.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Midnight Coffee
            [
                'user_id'      => 7,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 85,
                'musical_key'  => 'Fmaj',
                'tags'         => 'lofi,chill,study',
                'title'        => 'Midnight Coffee',
                'description'  => 'Lo-fi calme pour les sessions de travail.',
                'price'        => 25.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Neon Drive
            [
                'user_id'      => 8,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 128,
                'musical_key'  => 'Em',
                'tags'         => 'electro,synth,dance',
                'title'        => 'Neon Drive',
                'description'  => 'Beat électro énergique aux accents Retro-Wave.',
                'price'        => 55.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Spider-Sense
            [
                'user_id'      => 9,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 138,
                'musical_key'  => 'Bm',
                'tags'         => 'metro,trap,cinematic',
                'title'        => 'Spider-Sense',
                'description'  => 'Trap épique et cinématographique.',
                'price'        => 150.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Creepin Vibes
            [
                'user_id'      => 9,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 125,
                'musical_key'  => 'Fm',
                'tags'         => 'metro,dark,melodic',
                'title'        => 'Creepin Vibes',
                'description'  => 'Production sombre et mélodique typique du producteur.',
                'price'        => 130.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Compton Sunset
            [
                'user_id'      => 10,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 95,
                'musical_key'  => 'Cm',
                'tags'         => 'westcoast,gfunk,classic',
                'title'        => 'Compton Sunset',
                'description'  => 'G-Funk pur avec synthétiseurs West Coast.',
                'price'        => 200.00,
                'status'       => 'sold',
                'buyer_id'     => 2,
                'sold_at'      => $now,
                'is_featured'  => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Still Classic
            [
                'user_id'      => 10,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 92,
                'musical_key'  => 'Am',
                'tags'         => 'dre,piano,hiphop',
                'title'        => 'Still Classic',
                'description'  => 'Ligne de piano minimaliste et percutante.',
                'price'        => 250.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Donuts Legacy
            [
                'user_id'      => 11,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 92,
                'musical_key'  => 'D#m',
                'tags'         => 'soul,swing,dilla',
                'title'        => 'Donuts Legacy',
                'description'  => 'Beat soulful avec un swing unique aux pads.',
                'price'        => 100.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Quasimoto Mood
            [
                'user_id'      => 12,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 88,
                'musical_key'  => 'G#m',
                'tags'         => 'jazz,abstract,loop',
                'title'        => 'Quasimoto Mood',
                'description'  => 'Boucles jazz hypnotiques et abstraites.',
                'price'        => 80.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Medicine Show
            [
                'user_id'      => 12,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 94,
                'musical_key'  => 'Cmaj',
                'tags'         => 'madlib,experimental,sampling',
                'title'        => 'Medicine Show',
                'description'  => 'Collage sonore expérimental et percutant.',
                'price'        => 85.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Graduation Soul
            [
                'user_id'      => 13,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 80,
                'musical_key'  => 'Amaj',
                'tags'         => 'soul,chopped,classic',
                'title'        => 'Graduation Soul',
                'description'  => 'Sample de soul pitché et batterie percutante.',
                'price'        => 120.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // 808 Heartbreak
            [
                'user_id'      => 13,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 120,
                'musical_key'  => 'Abm',
                'tags'         => 'kanye,808,synth',
                'title'        => '808 Heartbreak',
                'description'  => 'Mélange de boites à rythme Roland et de synthétiseurs mélancoliques.',
                'price'        => 140.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Butterfly Effect
            [
                'user_id'      => 14,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 130,
                'musical_key'  => 'F#m',
                'tags'         => 'murda,trap,flute',
                'title'        => 'Butterfly Effect',
                'description'  => 'Banger trap avec mélodie de flûte entêtante.',
                'price'        => 70.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Star Trak Energy
            [
                'user_id'      => 15,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 115,
                'musical_key'  => 'Cmaj',
                'tags'         => 'funk,pop,happy',
                'title'        => 'Star Trak Energy',
                'description'  => 'Production funky avec les 4 coups de batterie signature.',
                'price'        => 90.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],

            // Neptune Glow
            [
                'user_id'      => 15,
                'category_id'  => $defaultCategoryId,
                'bpm'          => 105,
                'musical_key'  => 'Gmaj',
                'tags'         => 'pharrell,neptunes,groove',
                'title'        => 'Neptune Glow',
                'description'  => 'Groove futuriste et minimaliste.',
                'price'        => 95.00,
                'status'       => 'active',
                'buyer_id'     => null,
                'sold_at'      => null,
                'is_featured'  => 0,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ];

        $this->db->table('beats')->insertBatch($beats);
    }
}
