<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class GradesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'grade_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'course_enrollment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'student_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'prelim' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'midterm' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'pre_final' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'final' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'final_grade' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('grade_id', true);
        $this->forge->addKey('course_enrollment_id');
        $this->forge->addKey('student_id');

        $this->forge->addForeignKey('student_id', 'students', 'student_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('course_enrollment_id', 'courses_enrollments', 'course_enrollment_id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('grades');
    }

    public function down()
    {
        $this->forge->dropTable('grades');
    }
}
