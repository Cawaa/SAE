<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBeats extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],

            // Beatmaker (vendeur)
            'user_id' => ['type'=>'INT','unsigned'=>true],

            // Genre/style
            'category_id' => ['type'=>'INT','unsigned'=>true,'null'=>true],

            // Métadonnées musicales
            'bpm'         => ['type'=>'SMALLINT','unsigned'=>true,'null'=>true],
            'musical_key' => ['type'=>'VARCHAR','constraint'=>16,'null'=>true], // Am, F#m...
            'tags'        => ['type'=>'VARCHAR','constraint'=>255,'null'=>true], // CSV simple

            // Infos produit
            'title'       => ['type'=>'VARCHAR','constraint'=>180],
            'description' => ['type'=>'TEXT','null'=>true],
            'price'       => ['type'=>'DECIMAL','constraint'=>'10,2','default'=>'0.00'],

            // Exclusivité
            'status'   => ['type'=>'VARCHAR','constraint'=>20,'default'=>'active'], // active|sold|hidden|deleted
            'buyer_id' => ['type'=>'INT','unsigned'=>true,'null'=>true],
            'sold_at'  => ['type'=>'DATETIME','null'=>true],

            // Mise en avant (pour abo beatmaker)
            'is_featured' => ['type'=>'TINYINT','constraint'=>1,'default'=>0],

            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
        ]);

        $this->forge->addKey('id', true);

        // Index utiles recherche/tri
        $this->forge->addKey('user_id');
        $this->forge->addKey('category_id');
        $this->forge->addKey('status');
        $this->forge->addKey('bpm');
        $this->forge->addKey('musical_key');
        $this->forge->addKey('buyer_id');
        $this->forge->addKey(['is_featured','created_at']); // home “mise en avant + récent”

        $this->forge->addForeignKey('user_id','users','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('category_id','categories','id','SET NULL','CASCADE');
        $this->forge->addForeignKey('buyer_id','users','id','SET NULL','CASCADE');

        $this->forge->createTable('beats', true);
    }

    public function down()
    {
        $this->forge->dropTable('beats', true);
    }
}
