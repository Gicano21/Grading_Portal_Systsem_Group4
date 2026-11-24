<?php

namespace App\Controllers;

use App\Libraries\PortalData;
use CodeIgniter\Exceptions\PageNotFoundException;

class AdminController extends BaseController
{
    private const RECORD_TYPES = [
        'students' => [
            'title'      => 'Students',
            'table'      => 'students',
            'primaryKey' => 'student_id',
            'sort'       => 'last_name',
            'fields'     => [
                'first_name' => ['label' => 'First name', 'type' => 'text', 'rules' => 'required|max_length[100]'],
                'last_name'  => ['label' => 'Last name', 'type' => 'text', 'rules' => 'required|max_length[100]'],
                'middle_name'=> ['label' => 'Middle name (optional)', 'type' => 'text', 'rules' => 'permit_empty|max_length[100]', 'optional' => true],
                'extension'  => ['label' => 'Name extension (optional)', 'type' => 'text', 'rules' => 'permit_empty|max_length[10]', 'optional' => true],
                'gender'     => ['label' => 'Gender', 'type' => 'select', 'rules' => 'required|in_list[Male,Female]', 'options' => ['Male', 'Female']],
                'age'        => ['label' => 'Age', 'type' => 'number', 'rules' => 'required|is_natural_no_zero|less_than_equal_to[120]'],
                'email'      => ['label' => 'Email', 'type' => 'email', 'rules' => 'required|valid_email|max_length[100]'],
            ],
        ],
        'instructors' => [
            'title'      => 'Instructors',
            'table'      => 'instructors',
            'primaryKey' => 'instructor_id',
            'sort'       => 'last_name',
            'fields'     => [
                'first_name' => ['label' => 'First name', 'type' => 'text', 'rules' => 'required|max_length[100]'],
                'last_name'  => ['label' => 'Last name', 'type' => 'text', 'rules' => 'required|max_length[100]'],
                'middle_name'=> ['label' => 'Middle name (optional)', 'type' => 'text', 'rules' => 'permit_empty|max_length[100]', 'optional' => true],
                'extension'  => ['label' => 'Name extension (optional)', 'type' => 'text', 'rules' => 'permit_empty|max_length[10]', 'optional' => true],
                'gender'     => ['label' => 'Gender', 'type' => 'select', 'rules' => 'required|in_list[Male,Female]', 'options' => ['Male', 'Female']],
                'age'        => ['label' => 'Age', 'type' => 'number', 'rules' => 'required|is_natural_no_zero|less_than_equal_to[120]'],
                'email'      => ['label' => 'Email', 'type' => 'email', 'rules' => 'required|valid_email|max_length[100]'],
            ],
        ],
        'programs' => [
            'title'      => 'Programs',
            'table'      => 'programs',
            'primaryKey' => 'program_id',
            'sort'       => 'program_code',
            'fields'     => [
                'program_code' => ['label' => 'Program code', 'type' => 'text', 'rules' => 'required|max_length[100]'],
                'program'      => ['label' => 'Program name', 'type' => 'text', 'rules' => 'required|max_length[100]'],
            ],
        ],
        'courses' => [
            'title'      => 'Courses',
            'table'      => 'courses',
            'primaryKey' => 'course_id',
            'sort'       => 'course_code',
            'fields'     => [
                'course_code' => ['label' => 'Course code', 'type' => 'text', 'rules' => 'required|max_length[100]'],
                'course'      => ['label' => 'Course name', 'type' => 'text', 'rules' => 'required|max_length[100]'],
            ],
        ],
    ];

    public function index()
    {
        $portal = new PortalData();

        return view('admin/index', [
            'stats' => $portal->dashboardStats(),
        ]);
    }

    public function records(string $entity)
    {
        $config = $this->recordType($entity);
        $records = db_connect()->table($config['table'])
            ->orderBy($config['sort'])
            ->get()
            ->getResultArray();

        return view('admin/records', [
            'entity'  => $entity,
            'config'  => $config,
            'records' => $records,
        ]);
    }

    public function createRecord(string $entity)
    {
        return $this->saveRecord($entity);
    }

    public function editRecord(string $entity, int $id)
    {
        $config = $this->recordType($entity);
        $record = db_connect()->table($config['table'])
            ->where($config['primaryKey'], $id)
            ->get()
            ->getRowArray();

        if ($record === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('admin/record_form', [
            'entity' => $entity,
            'config' => $config,
            'record' => $record,
        ]);
    }

    public function updateRecord(string $entity, int $id)
    {
        return $this->saveRecord($entity, $id);
    }

    public function deleteRecord(string $entity, int $id)
    {
        $config = $this->recordType($entity);
        $db = db_connect();
        $record = $db->table($config['table'])
            ->where($config['primaryKey'], $id)
            ->get()
            ->getRowArray();

        if ($record === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        if ($this->recordHasDependents($entity, $id)) {
            return redirect()->to(site_url('admin/records/' . $entity))
                ->with('error', 'This record is in use and cannot be deleted.');
        }

        if (! $db->table($config['table'])->where($config['primaryKey'], $id)->delete()) {
            return redirect()->to(site_url('admin/records/' . $entity))
                ->with('error', 'The record could not be deleted.');
        }

        return redirect()->to(site_url('admin/records/' . $entity))
            ->with('message', 'Record deleted.');
    }

    private function saveRecord(string $entity, ?int $id = null)
    {
        $config = $this->recordType($entity);
        $rules = [];
        $data = [];

        foreach ($config['fields'] as $name => $field) {
            $rules[$name] = $field['rules'];
            $value = $this->request->getPost($name);

            if ($value !== null && ! is_scalar($value)) {
                return redirect()->back()->with('error', 'Please submit valid record details.');
            }

            $value = trim((string) ($value ?? ''));
            $data[$name] = $field['type'] === 'number'
                ? (int) $value
                : ($value === '' ? null : $value);
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('error', 'Please correct the highlighted record details.');
        }

        $db = db_connect();
        foreach (['program_code', 'course_code'] as $uniqueField) {
            if (! array_key_exists($uniqueField, $data)) {
                continue;
            }

            $query = $db->table($config['table'])->where($uniqueField, $data[$uniqueField]);
            if ($id !== null) {
                $query->where($config['primaryKey'] . ' !=', $id);
            }

            if ($query->countAllResults() > 0) {
                return redirect()->back()->withInput()
                    ->with('error', 'That code is already in use.');
            }
        }

        if ($id === null) {
            $saved = $db->table($config['table'])->insert($data);
        } else {
            $saved = $db->table($config['table'])
                ->where($config['primaryKey'], $id)
                ->update($data);
        }

        if (! $saved) {
            return redirect()->back()->withInput()
                ->with('error', 'The record could not be saved.');
        }

        return redirect()->to(site_url('admin/records/' . $entity))
            ->with('message', $id === null ? 'Record created.' : 'Record updated.');
    }

    private function recordHasDependents(string $entity, int $id): bool
    {
        $db = db_connect();

        if ($entity === 'students') {
            $hasStudentRecords = $db->table('enrolments')->where('student_id', $id)->countAllResults() > 0
                || $db->table('grades')->where('student_id', $id)->countAllResults() > 0;
            $hasStudentAccount = $db->table('users u')
                ->join('auth_groups_users gu', 'gu.user_id = u.id')
                ->where('u.role_id', $id)
                ->where('gu.group', 'student')
                ->countAllResults() > 0;

            return $hasStudentRecords || $hasStudentAccount;
        }

        if ($entity === 'instructors') {
            $hasAssignedClasses = $db->table('class_offerings')->where('instructor_id', $id)->countAllResults() > 0
                || $db->table('courses_enrollments')->where('instructor_id', $id)->countAllResults() > 0;
            $hasInstructorAccount = $db->table('users u')
                ->join('auth_groups_users gu', 'gu.user_id = u.id')
                ->where('u.role_id', $id)
                ->where('gu.group', 'instructor')
                ->countAllResults() > 0;

            return $hasAssignedClasses || $hasInstructorAccount;
        }

        $references = [
            'programs' => [
                ['table' => 'enrolments', 'column' => 'program_id'],
            ],
            'courses'  => [
                ['table' => 'class_offerings', 'column' => 'course_id'],
                ['table' => 'courses_enrollments', 'column' => 'course_id'],
            ],
        ];

        foreach ($references[$entity] as $reference) {
            if ($db->table($reference['table'])->where($reference['column'], $id)->countAllResults() > 0) {
                return true;
            }
        }

        return false;
    }

    private function recordType(string $entity): array
    {
        if (! isset(self::RECORD_TYPES[$entity])) {
            throw PageNotFoundException::forPageNotFound();
        }

        return self::RECORD_TYPES[$entity];
    }
}
