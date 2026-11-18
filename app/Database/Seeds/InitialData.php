<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialData extends Seeder
{
    public function run()
    {
        $this->seedData('students', [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'gender' => 'Male',
                'age' => 20,
                'email' => 'john.doe@example.com',
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'gender' => 'Female',
                'age' => 21,
                'email' => 'jane.smith@example.com',
            ],
        ]);

        $this->seedData('accounts', [
            [
                'username' => 'admin',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'Admin',
                'role_id' => null,
            ],
            [
                'username' => 'registrar',
                'password' => password_hash('registrar123', PASSWORD_DEFAULT),
                'role' => 'Registrar',
                'role_id' => null,
            ],
            [
                'username' => 'teacher1',
                'password' => password_hash('teacher123', PASSWORD_DEFAULT),
                'role' => 'Instructor',
                'role_id' => 1,
            ],
            [
                'username' => 'student1',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role' => 'Student',
                'role_id' => 1,
            ],
            [
                'username' => 'student2',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role' => 'Student',
                'role_id' => 2,
            ],
        ]);

        $this->seedData('courses', [
            [
                'course_code' => 'CS101',
                'course' => 'Introduction to Computer Science',
            ],
            [
                'course_code' => 'MATH201',
                'course' => 'Calculus I',
            ],
        ]);

        $this->seedData('programs', [
            [
                'program_code' => 'BSCS',
                'program' => 'Bachelor of Science in Computer Science',
            ],
            [
                'program_code' => 'BSIT',
                'program' => 'Bachelor of Science in Information Technology',
            ],
        ]);

        $this->seedData('sections', [
            [
                'section' => 'Section A1',
            ],
            [
                'section' => 'Section B1',
            ],
        ]);

        $this->seedData('academic_years', [
            [
                'academic_year' => '2023-2024',
                'semester' => 'First Semester',
            ],
            [
                'academic_year' => '2024-2025',
                'semester' => 'First Semester',
            ],
        ]);

        $this->seedData('enrolments', [
            [
                'year_level' => 1,
                'program_id' => 1,
                'student_id' => 1,
                'academic_year_id' => 1,
                'section_id' => 1,
            ],
            [
                'year_level' => 2,
                'program_id' => 2,
                'student_id' => 2,
                'academic_year_id' => 1,
                'section_id' => 2,
            ],
        ]);

        $this->seedData('instructors', [
            [
                'instructor_id' => 1,
                'last_name' => 'Smith',
                'first_name' => 'Jane',
                'middle_name' => null,
                'extension' => null,
                'gender' => 'Female',
                'age' => 35,
                'email' => 'jane.smith@example.com',
            ],
        ]);

        $this->seedData('class_offerings', [
            [
                'course_id' => 1,
                'section_id' => 1,
                'academic_year_id' => 1,
                'instructor_id' => 1,
            ],
            [
                'course_id' => 2,
                'section_id' => 2,
                'academic_year_id' => 1,
                'instructor_id' => 1,
            ],
        ]);

        $this->seedData('courses_enrollments', [
            [
                'course_id' => 1,
                'enrollment_id' => 1,
                'instructor_id' => 1,
                'class_offering_id' => 1,
            ],
            [
                'course_id' => 2,
                'enrollment_id' => 2,
                'instructor_id' => 1,
                'class_offering_id' => 2,
            ],
        ]);

        $this->seedData('grades', [
            [
                'course_enrollment_id' => 1,
                'student_id' => 1,
                'prelim' => 85.00,
                'midterm' => 90.00,
                'pre_final' => 88.00,
                'final' => 92.00,
                'final_grade' => 88.75,
            ],
            [
                'course_enrollment_id' => 2,
                'student_id' => 2,
                'prelim' => 78.00,
                'midterm' => 82.00,
                'pre_final' => 80.00,
                'final' => 85.00,
                'final_grade' => 81.25,
            ],
        ]);

    }

    public function seedData($table, $data)
    {
        return $this->db->table($table)->insertBatch($data);
    }
}
