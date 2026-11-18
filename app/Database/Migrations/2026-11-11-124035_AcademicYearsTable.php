<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AcademicYearsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'academic_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'academic_year' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'semester' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
        ]);
        $this->forge->addKey('academic_id', true);

        $this->forge->createTable('academic_years');
    }

    public function down()
    {
        $this->forge->dropTable('academic_years');
    }
}
