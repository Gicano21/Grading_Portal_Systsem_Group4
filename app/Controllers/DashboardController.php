<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        $destinations = [
            'Admin'      => 'admin',
            'Registrar'  => 'registrar',
            'Instructor' => 'instructor/classes',
            'Student'    => 'student/grades',
        ];
        $role = session('user.role');

        if (! isset($destinations[$role])) {
            session()->destroy();

            return redirect()->to(site_url('login'))->with('error', 'Your account role is not recognized. Please sign in again.');
        }

        return redirect()->to(site_url($destinations[$role]));
    }
}
