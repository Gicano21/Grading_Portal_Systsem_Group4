<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InstructorsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'instructor_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'last_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'middle_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'extension' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
            ],
            'gender' => [
                'type' => 'ENUM',
                'constraint' => ['Male', 'Female'],
            ],
            'age' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
        ]);
        $this->forge->addKey('instructor_id', true);

        $this->forge->createTable('instructors');
    }

    public function down()
    {
        $this->forge->dropTable('instructors');
    }
}
