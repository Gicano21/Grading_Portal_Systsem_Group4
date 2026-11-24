<?php

namespace App\Libraries;

use CodeIgniter\Database\BaseConnection;

class PortalData
{
    private BaseConnection $db;

    public function __construct()
    {
        $this->db = db_connect();
    }

    public function classes(?int $instructorId = null): array
    {
        $builder = $this->db->table('class_offerings co')
            ->select(
                'co.class_offering_id, co.instructor_id, c.course_code, c.course, s.section, ' .
                'ay.academic_year, ay.semester, i.first_name AS instructor_first_name, ' .
                'i.last_name AS instructor_last_name, ' .
                '(SELECT COUNT(*) FROM courses_enrollments ce ' .
                'JOIN enrolments e ON e.enrollment_id = ce.enrollment_id ' .
                'WHERE ce.class_offering_id = co.class_offering_id) AS student_count',
            )
            ->join('courses c', 'c.course_id = co.course_id')
            ->join('sections s', 's.section_id = co.section_id')
            ->join('academic_years ay', 'ay.academic_id = co.academic_year_id')
            ->join('instructors i', 'i.instructor_id = co.instructor_id')
            ->orderBy('ay.academic_year', 'DESC')
            ->orderBy('c.course_code')
            ->orderBy('s.section');

        if ($instructorId !== null) {
            $builder->where('co.instructor_id', $instructorId);
        }

        return $builder->get()->getResultArray();
    }

    public function classOffering(int $classOfferingId, ?int $instructorId = null): ?array
    {
        $builder = $this->db->table('class_offerings co')
            ->select(
                'co.class_offering_id, co.course_id, co.section_id, co.academic_year_id, co.instructor_id, ' .
                'c.course_code, c.course, s.section, ay.academic_year, ay.semester, ' .
                'i.first_name AS instructor_first_name, i.last_name AS instructor_last_name',
            )
            ->join('courses c', 'c.course_id = co.course_id')
            ->join('sections s', 's.section_id = co.section_id')
            ->join('academic_years ay', 'ay.academic_id = co.academic_year_id')
            ->join('instructors i', 'i.instructor_id = co.instructor_id')
            ->where('co.class_offering_id', $classOfferingId);

        if ($instructorId !== null) {
            $builder->where('co.instructor_id', $instructorId);
        }

        $result = $builder->get()->getRowArray();

        return $result === null ? null : $result;
    }

    public function classRoster(int $classOfferingId): array
    {
        return $this->db->table('courses_enrollments ce')
            ->select(
                'ce.course_enrollment_id, e.student_id, s.first_name, s.last_name, s.email, ' .
                'g.prelim, g.midterm, g.pre_final, g.final, g.final_grade',
            )
            ->join('enrolments e', 'e.enrollment_id = ce.enrollment_id')
            ->join('students s', 's.student_id = e.student_id')
            ->join('grades g', 'g.course_enrollment_id = ce.course_enrollment_id AND g.student_id = e.student_id', 'left')
            ->where('ce.class_offering_id', $classOfferingId)
            ->orderBy('s.last_name')
            ->orderBy('s.first_name')
            ->get()
            ->getResultArray();
    }

    public function studentGrades(int $studentId): array
    {
        return $this->db->table('courses_enrollments ce')
            ->select(
                'ce.course_enrollment_id, c.course_code, c.course, s.section, ay.academic_year, ay.semester, ' .
                'i.first_name AS instructor_first_name, i.last_name AS instructor_last_name, ' .
                'g.prelim, g.midterm, g.pre_final, g.final, g.final_grade',
            )
            ->join('enrolments e', 'e.enrollment_id = ce.enrollment_id')
            ->join('class_offerings co', 'co.class_offering_id = ce.class_offering_id')
            ->join('courses c', 'c.course_id = co.course_id')
            ->join('sections s', 's.section_id = co.section_id')
            ->join('academic_years ay', 'ay.academic_id = co.academic_year_id')
            ->join('instructors i', 'i.instructor_id = co.instructor_id')
            ->join('grades g', 'g.course_enrollment_id = ce.course_enrollment_id AND g.student_id = e.student_id', 'left')
            ->where('e.student_id', $studentId)
            ->orderBy('ay.academic_year', 'DESC')
            ->orderBy('c.course_code')
            ->get()
            ->getResultArray();
    }

    public function students(): array
    {
        return $this->db->table('students')
            ->select('student_id, first_name, last_name, email')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->getResultArray();
    }

    public function searchStudents(string $query): array
    {
        $builder = $this->db->table('students')
            ->select('student_id, first_name, last_name, email')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->limit(50);

        if ($query !== '') {
            $builder->groupStart()
                ->like('first_name', $query)
                ->orLike('last_name', $query)
                ->orLike('email', $query);

            if (ctype_digit($query)) {
                $builder->orWhere('student_id', (int) $query);
            }

            $builder->groupEnd();
        }

        return $builder->get()->getResultArray();
    }

    public function student(int $studentId): ?array
    {
        $student = $this->db->table('students')
            ->where('student_id', $studentId)
            ->get()
            ->getRowArray();

        return $student;
    }

    public function programCourseGrades(int $programId, int $courseId): array
    {
        return $this->db->table('courses_enrollments ce')
            ->select(
                's.student_id, s.first_name, s.last_name, s.email, p.program_code, p.program, ' .
                'c.course_code, c.course, sec.section, ay.academic_year, ay.semester, ' .
                'i.first_name AS instructor_first_name, i.last_name AS instructor_last_name, ' .
                'g.prelim, g.midterm, g.pre_final, g.final, g.final_grade',
            )
            ->join('enrolments e', 'e.enrollment_id = ce.enrollment_id')
            ->join('students s', 's.student_id = e.student_id')
            ->join('programs p', 'p.program_id = e.program_id')
            ->join('class_offerings co', 'co.class_offering_id = ce.class_offering_id')
            ->join('courses c', 'c.course_id = co.course_id')
            ->join('sections sec', 'sec.section_id = co.section_id')
            ->join('academic_years ay', 'ay.academic_id = co.academic_year_id')
            ->join('instructors i', 'i.instructor_id = co.instructor_id')
            ->join('grades g', 'g.course_enrollment_id = ce.course_enrollment_id AND g.student_id = e.student_id', 'left')
            ->where('e.program_id', $programId)
            ->where('co.course_id', $courseId)
            ->orderBy('s.last_name')
            ->orderBy('s.first_name')
            ->orderBy('ay.academic_year', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function courses(): array
    {
        return $this->db->table('courses')->orderBy('course_code')->get()->getResultArray();
    }

    public function sections(): array
    {
        return $this->db->table('sections')->orderBy('section')->get()->getResultArray();
    }

    public function academicYears(): array
    {
        return $this->db->table('academic_years')
            ->orderBy('academic_year', 'DESC')
            ->orderBy('semester')
            ->get()
            ->getResultArray();
    }

    public function programs(): array
    {
        return $this->db->table('programs')->orderBy('program_code')->get()->getResultArray();
    }

    public function instructors(): array
    {
        return $this->db->table('instructors')
            ->select('instructor_id, first_name, last_name')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->getResultArray();
    }

    public function dashboardStats(): array
    {
        return [
            'students'  => $this->db->table('students')->countAllResults(),
            'instructors' => $this->db->table('instructors')->countAllResults(),
            'courses'   => $this->db->table('courses')->countAllResults(),
            'programs'  => $this->db->table('programs')->countAllResults(),
            'classes'   => $this->db->table('class_offerings')->countAllResults(),
        ];
    }
}
