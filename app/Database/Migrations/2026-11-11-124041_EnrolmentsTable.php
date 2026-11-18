<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnrolmentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'enrollment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'year_level' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'program_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'student_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'academic_year_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'section_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);
        $this->forge->addKey('enrollment_id', true);

        $this->forge->addForeignKey('program_id', 'programs', 'program_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('student_id', 'students', 'student_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('academic_year_id', 'academic_years', 'academic_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('section_id', 'sections', 'section_id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('enrolments');
    }

    public function down()
    {
        $this->forge->dropTable('enrolments');
    }
}
