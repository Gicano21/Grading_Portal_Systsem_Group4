<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CoursesEnrollmentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'course_enrollment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'course_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'enrollment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'instructor_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'class_offering_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);
        $this->forge->addKey('course_enrollment_id', true);
        $this->forge->addUniqueKey(['class_offering_id', 'enrollment_id']);

        $this->forge->addForeignKey('course_id', 'courses', 'course_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('enrollment_id', 'enrolments', 'enrollment_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('instructor_id', 'instructors', 'instructor_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('class_offering_id', 'class_offerings', 'class_offering_id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('courses_enrollments');
    }

    public function down()
    {
        $this->forge->dropTable('courses_enrollments');
    }
}
