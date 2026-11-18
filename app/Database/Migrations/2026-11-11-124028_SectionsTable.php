<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SectionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'section_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'section' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
        ]);
        $this->forge->addKey('section_id', true);

        $this->forge->createTable('sections');
    }

    public function down()
    {
        $this->forge->dropTable('sections');
    }
}
