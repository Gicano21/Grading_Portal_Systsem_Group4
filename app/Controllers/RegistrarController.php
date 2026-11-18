<?php

namespace App\Controllers;

use App\Libraries\PortalData;
use CodeIgniter\Exceptions\PageNotFoundException;

class RegistrarController extends BaseController
{
    public function index()
    {
        $portal = new PortalData();

        return view('registrar/index', [
            'stats'        => $portal->dashboardStats(),
            'classes'      => $portal->classes(),
            'students'     => $portal->students(),
            'courses'      => $portal->courses(),
            'sections'     => $portal->sections(),
            'academicYears'=> $portal->academicYears(),
            'instructors'  => $portal->instructors(),
            'programs'     => $portal->programs(),
        ]);
    }

    public function createSection()
    {
        $section = trim((string) $this->request->getPost('section'));
        if ($section === '' || mb_strlen($section) > 100) {
            return redirect()->to(site_url('registrar'))->withInput()->with('error', 'Enter a section name of up to 100 characters.');
        }

        $db = db_connect();
        if ($db->table('sections')->where('section', $section)->countAllResults() > 0) {
            return redirect()->to(site_url('registrar'))->withInput()->with('error', 'That section already exists.');
        }

        $db->table('sections')->insert(['section' => $section]);

        return redirect()->to(site_url('registrar'))->with('message', 'Section created.');
    }

    public function createClass()
    {
        $db = db_connect();
        $courseId = (int) $this->request->getPost('course_id');
        $sectionId = (int) $this->request->getPost('section_id');
        $academicYearId = (int) $this->request->getPost('academic_year_id');
        $instructorId = (int) $this->request->getPost('instructor_id');
        $valid = $this->validate([
            'course_id'        => 'required|is_natural_no_zero|is_not_unique[courses.course_id]',
            'section_id'       => 'required|is_natural_no_zero|is_not_unique[sections.section_id]',
            'academic_year_id' => 'required|is_natural_no_zero|is_not_unique[academic_years.academic_id]',
            'instructor_id'    => 'required|is_natural_no_zero|is_not_unique[instructors.instructor_id]',
        ]);
        if (! $valid) {
            return redirect()->to(site_url('registrar'))->withInput()->with('error', 'Choose a valid course, section, academic year, and instructor.');
        }

        $exists = $db->table('class_offerings')
            ->where('course_id', $courseId)
            ->where('section_id', $sectionId)
            ->where('academic_year_id', $academicYearId)
            ->countAllResults();
        if ($exists > 0) {
            return redirect()->to(site_url('registrar'))->with('error', 'That course is already offered for the selected section and academic year.');
        }

        $db->table('class_offerings')->insert([
            'course_id'        => $courseId,
            'section_id'       => $sectionId,
            'academic_year_id' => $academicYearId,
            'instructor_id'    => $instructorId,
        ]);

        return redirect()->to(site_url('registrar'))->with('message', 'Class offering created.');
    }

    public function enrollStudent()
    {
        $db = db_connect();
        $classOfferingId = (int) $this->request->getPost('class_offering_id');
        $studentId = (int) $this->request->getPost('student_id');
        $programId = (int) $this->request->getPost('program_id');
        $yearLevel = (int) $this->request->getPost('year_level');

        if (! $this->validate([
            'class_offering_id' => 'required|is_natural_no_zero|is_not_unique[class_offerings.class_offering_id]',
            'student_id'        => 'required|is_natural_no_zero|is_not_unique[students.student_id]',
            'program_id'        => 'required|is_natural_no_zero|is_not_unique[programs.program_id]',
            'year_level'        => 'required|is_natural_no_zero|less_than_equal_to[10]',
        ])) {
            return redirect()->to(site_url('registrar'))->withInput()->with('error', 'Select a class, student, program, and valid year level.');
        }

        $class = (new PortalData())->classOffering($classOfferingId);
        if ($class === null) {
            return redirect()->to(site_url('registrar'))->with('error', 'The selected student, program, or class was not found.');
        }

        $enrollment = $db->table('enrolments')
            ->where('student_id', $studentId)
            ->where('program_id', $programId)
            ->where('academic_year_id', $class['academic_year_id'])
            ->where('section_id', $class['section_id'])
            ->get()
            ->getRowArray();

        $db->transStart();
        if ($enrollment === null) {
            $db->table('enrolments')->insert([
                'student_id'      => $studentId,
                'program_id'      => $programId,
                'year_level'      => $yearLevel,
                'academic_year_id'=> $class['academic_year_id'],
                'section_id'      => $class['section_id'],
            ]);
            $enrollmentId = (int) $db->insertID();
        } else {
            $enrollmentId = (int) $enrollment['enrollment_id'];
        }

        $duplicate = $db->table('courses_enrollments')
            ->where('class_offering_id', $classOfferingId)
            ->where('enrollment_id', $enrollmentId)
            ->countAllResults();
        if ($duplicate > 0) {
            $db->transRollback();

            return redirect()->to(site_url('registrar'))->with('error', 'That student is already enrolled in this class.');
        }

        $db->table('courses_enrollments')->insert([
            'course_id'         => $class['course_id'],
            'enrollment_id'     => $enrollmentId,
            'instructor_id'     => $class['instructor_id'],
            'class_offering_id' => $classOfferingId,
        ]);
        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->to(site_url('registrar'))->with('error', 'Enrollment could not be saved.');
        }

        return redirect()->to(site_url('registrar'))->with('message', 'Student enrolled in the class.');
    }

    public function assignFaculty(int $classOfferingId)
    {
        $db = db_connect();
        $instructorId = (int) $this->request->getPost('instructor_id');
        $class = (new PortalData())->classOffering($classOfferingId);
        if ($class === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (! $this->validate([
            'instructor_id' => 'required|is_natural_no_zero|is_not_unique[instructors.instructor_id]',
        ])) {
            return redirect()->to(site_url('registrar'))->with('error', 'Choose a valid instructor.');
        }

        $db->transStart();
        $db->table('class_offerings')
            ->where('class_offering_id', $classOfferingId)
            ->update(['instructor_id' => $instructorId]);
        $db->table('courses_enrollments')
            ->where('class_offering_id', $classOfferingId)
            ->update(['instructor_id' => $instructorId]);
        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->to(site_url('registrar'))->with('error', 'Faculty assignment could not be saved.');
        }

        return redirect()->to(site_url('registrar'))->with('message', 'Instructor assigned to the class.');
    }

    public function report(int $classOfferingId)
    {
        $portal = new PortalData();
        $class = $portal->classOffering($classOfferingId);
        if ($class === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('registrar/report', [
            'class'  => $class,
            'roster' => $portal->classRoster($classOfferingId),
        ]);
    }
}
