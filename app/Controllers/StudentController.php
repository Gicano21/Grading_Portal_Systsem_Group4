<?php

namespace App\Controllers;

use App\Libraries\PortalData;

class StudentController extends BaseController
{
    public function grades()
    {
        $user = auth()->user();
        $student = db_connect()->table('students')
            ->where('student_id', $user->role_id)
            ->get()
            ->getRowArray();

        return view('student/grades', [
            'student' => $student,
            'grades'  => (new PortalData())->studentGrades((int) $user->role_id),
        ]);
    }
}
