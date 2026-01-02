<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBeatFiles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'      => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
            'beat_id' => ['type'=>'INT','unsigned'=>true],

            // preview_mp3 | original_wav | original_mp3 | stems_zip | cover_image
            'type' => ['type'=>'VARCHAR','constraint'=>30],

            'path'         => ['type'=>'VARCHAR','constraint'=>255],
            'mime_type'    => ['type'=>'VARCHAR','constraint'=>80,'null'=>true],
            'size_bytes'   => ['type'=>'INT','unsigned'=>true,'null'=>true],
            'duration_sec' => ['type'=>'INT','unsigned'=>true,'null'=>true],

            'created_at' => ['type'=>'DATETIME','null'=>true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('beat_id');
        $this->forge->addKey('type');

        // Pour éviter 2 previews_mp3 par exemple
        $this->forge->addUniqueKey(['beat_id','type']);

        $this->forge->addForeignKey('beat_id','beats','id','CASCADE','CASCADE');

        $this->forge->createTable('beat_files', true);
    }

    public function down()
    {
        $this->forge->dropTable('beat_files', true);
    }
}
