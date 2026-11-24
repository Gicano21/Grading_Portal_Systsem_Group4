<?php

namespace App\Database\Seeds;

use App\Models\UserModel;
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

        $this->seedUsers([
            [
                'username' => 'admin',
                'role' => 'Admin',
                'role_id' => null,
                'password' => 'admin123',
            ],
            [
                'username' => 'registrar',
                'role' => 'Registrar',
                'role_id' => null,
                'password' => 'registrar123',
            ],
            [
                'username' => 'teacher1',
                'role' => 'Instructor',
                'role_id' => 1,
                'password' => 'teacher123',
            ],
            [
                'username' => 'student1',
                'role' => 'Student',
                'role_id' => 1,
                'password' => 'student123',
            ],
            [
                'username' => 'student2',
                'role' => 'Student',
                'role_id' => 2,
                'password' => 'student123',
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

    private function seedUsers(array $users): void
    {
        $userModel = model(UserModel::class);

        foreach ($users as $userData) {
            $user = $userModel->createNewUser([
                'username' => $userData['username'],
                'email'    => $userData['username'],
                'password' => $userData['password'],
                'active'   => true,
                'role_id'  => $userData['role_id'],
            ]);

            $userModel->save($user);
            $savedUser = $userModel->findByCredentials([
                'username' => $userData['username'],
            ]);

            if ($savedUser === null) {
                throw new \RuntimeException('Unable to load seeded Shield user: ' . $userData['username']);
            }

            $savedUser->addGroup(strtolower($userData['role']));
        }
    }
}
