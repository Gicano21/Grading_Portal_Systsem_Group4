<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function login()
    {
        if (auth()->loggedIn()) {
            return redirect()->to(site_url('dashboard'));
        }

        return view('auth/login', [
            'username' => old('username', ''),
            'errors'   => session('validationErrors') ?? [],
        ]);
    }

    public function authenticate()
    {
        $rules = [
            'username' => 'required|max_length[30]',
            'password' => 'required|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('validationErrors', $this->validator->getErrors());
        }

        $result = auth()->attempt([
            'username' => trim((string) $this->request->getPost('username')),
            'password' => (string) $this->request->getPost('password'),
        ]);

        if (! $result->isOK()) {
            return redirect()->back()->withInput()->with('error', 'The username or password is incorrect.');
        }

        $user = auth()->user();

        if (! $user->inGroup('admin', 'registrar', 'instructor', 'student')) {
            auth()->logout();

            return redirect()->back()->withInput()->with('error', 'This account does not have a supported role.');
        }

        if ($user->inGroup('student') && ! db_connect()->table('students')->where('student_id', $user->role_id)->countAllResults()) {
            auth()->logout();

            return redirect()->back()->withInput()->with('error', 'This student account is not linked to a student record.');
        }

        if ($user->inGroup('instructor') && ! db_connect()->table('instructors')->where('instructor_id', $user->role_id)->countAllResults()) {
            auth()->logout();

            return redirect()->back()->withInput()->with('error', 'This instructor account is not linked to an instructor record.');
        }

        return redirect()->to(site_url('dashboard'));
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->to(site_url('login'))->with('message', 'You have been signed out.');
    }
}
