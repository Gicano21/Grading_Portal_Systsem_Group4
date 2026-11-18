<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProgramsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'program_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'program_code' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'program' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
        ]);
        $this->forge->addKey('program_id', true);

        $this->forge->createTable('programs');
    }

    public function down()
    {
        $this->forge->dropTable('programs');
    }
}