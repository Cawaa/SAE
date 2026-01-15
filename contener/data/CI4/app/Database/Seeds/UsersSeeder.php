<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        $users = [
            [
                'email' => 'admin@tempo.test',
                'username' => 'admin',
                'password_hash' => password_hash('admin0', PASSWORD_DEFAULT),
                'role' => 'admin',
                'avatar' => null,
                'artist_genre' => null,
                'created_at' => $now,
            ],
            [
                'email' => 'buyer@tempo.test',
                'username' => 'buyer',
                'password_hash' => password_hash('buyer0', PASSWORD_DEFAULT),
                'role' => 'user',
                'avatar' => 'avatars/buyer.jpg',
                'artist_genre' => null,
                'created_at' => $now,
            ],
            [
                'email' => 'viperbeats@tempo.test',
                'username' => 'Viper Beats',
                'password_hash' => password_hash('test0', PASSWORD_DEFAULT),
                'role' => 'user',
                'avatar' => 'avatars/prod1.jpg',
                'artist_genre' => 'Trap',
                'created_at' => $now,
            ],
            [
                'email' => 'shadowonthetrack@tempo.test',
                'username' => 'Shadow On The Track',
                'password_hash' => password_hash('test0', PASSWORD_DEFAULT),
                'role' => 'user',
                'avatar' => 'avatars/prod2.jpg',
                'artist_genre' => 'Drill',
                'created_at' => $now,
            ],
            [
                'email' => 'glitchkid@tempo.test',
                'username' => 'Glitch Kid',
                'password_hash' => password_hash('test0', PASSWORD_DEFAULT),
                'role' => 'user',
                'avatar' => 'avatars/prod3.jpg',
                'artist_genre' => null,
                'created_at' => $now,
            ],
            [
                'email' => 'vlad@tempo.test',
                'username' => 'VladTheProducer',
                'password_hash' => password_hash('vlad0', PASSWORD_DEFAULT),
                'role' => 'user',
                'avatar' => 'avatars/vlad.png',
                'artist_genre' => 'Boom Bap',
                'created_at' => $now,
            ],
            [
                'email' => 'seraph@tempo.test',
                'username' => 'Seraph1m',
                'password_hash' => password_hash('seraph0', PASSWORD_DEFAULT),
                'role' => 'user',
                'avatar' => 'avatars/seraph1m.png',
                'artist_genre' => 'Lo-Fi',
                'created_at' => $now,
            ],
            [
                'email' => 'perceval@tempo.test',
                'username' => 'PercevalBeats',
                'password_hash' => password_hash('test0', PASSWORD_DEFAULT),
                'role' => 'user',
                'avatar' => 'avatars/perceval.png',
                'artist_genre' => 'Electro',
                'created_at' => $now,
            ],
            [
                'email' => 'metro@tempo.test',
                'username' => 'Metro Boomin',
                'password_hash' => password_hash('metro0', PASSWORD_DEFAULT),
                'role' => 'user',
                'avatar' => 'avatars/metro_boomin.jpg',
                'artist_genre' => 'Trap',
                'created_at' => $now,
            ],
            [
                'email' => 'dre@tempo.test',
                'username' => 'Dr. Dre',
                'password_hash' => password_hash('dre0', PASSWORD_DEFAULT),
                'role' => 'user',
                'avatar' => 'avatars/dr_dre.jpg',
                'artist_genre' => 'West Coast',
                'created_at' => $now,
            ],
            [
                'email' => 'dilla@tempo.test',
                'username' => 'J Dilla',
                'password_hash' => password_hash('dilla0', PASSWORD_DEFAULT),
                'role' => 'user',
                'avatar' => 'avatars/j_dilla.jpg',
                'artist_genre' => 'Boom Bap',
                'created_at' => $now,
            ],
            [
                'email' => 'madlib@tempo.test',
                'username' => 'Madlib',
                'password_hash' => password_hash('madlib0', PASSWORD_DEFAULT),
                'role' => 'user',
                'avatar' => 'avatars/madlib.jpg',
                'artist_genre' => 'Lo-Fi',
                'created_at' => $now,
            ],
            [
                'email' => 'kanye@tempo.test',
                'username' => 'Kanye West',
                'password_hash' => password_hash('kanye0', PASSWORD_DEFAULT),
                'role' => 'user',
                'avatar' => 'avatars/kanye_west.jpg',
                'artist_genre' => 'Soul Sample',
                'created_at' => $now,
            ],
            [
                'email' => 'murda@tempo.test',
                'username' => 'Murda Beatz',
                'password_hash' => password_hash('murda0', PASSWORD_DEFAULT),
                'role' => 'user',
                'avatar' => 'avatars/murda_beatz.jpg',
                'artist_genre' => 'Modern Trap',
                'created_at' => $now,
            ],
            [
                'email' => 'phrell@tempo.test',
                'username' => 'Pharrell Williams',
                'password_hash' => password_hash('pharrell0', PASSWORD_DEFAULT),
                'role' => 'user',
                'avatar' => 'avatars/pharrell_williams.jpg',
                'artist_genre' => 'Funk',
                'created_at' => $now,
            ],
        ];

        foreach ($users as $u) {
            $exists = $this->db->table('users')
                ->where('email', $u['email'])
                ->countAllResults();

            if ($exists === 0) {
                $this->db->table('users')->insert($u);
            }
        }
    }
}
