<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ClassOfferingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'class_offering_id' => [
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
            'section_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'academic_year_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'instructor_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);
        $this->forge->addKey('class_offering_id', true);
        $this->forge->addUniqueKey(['course_id', 'section_id', 'academic_year_id']);
        $this->forge->addForeignKey('course_id', 'courses', 'course_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('section_id', 'sections', 'section_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('academic_year_id', 'academic_years', 'academic_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('instructor_id', 'instructors', 'instructor_id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('class_offerings');
    }

    public function down()
    {
        $this->forge->dropTable('class_offerings');
    }
}
