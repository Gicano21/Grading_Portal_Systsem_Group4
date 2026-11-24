<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index()
    {
        $user = auth()->user();

        foreach ([
            'admin'      => 'admin/dashboard',
            'registrar'  => 'registrar',
            'instructor' => 'instructor/classes',
            'student'    => 'student/grades',
        ] as $group => $destination) {
            if ($user->inGroup($group)) {
                return redirect()->to(site_url($destination));
            }
        }

        auth()->logout();

        return redirect()->to(site_url('login'))->with('error', 'Your account role is not recognized. Please sign in again.');
    }
}
