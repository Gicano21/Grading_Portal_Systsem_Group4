<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function login()
    {
        if (session('user')) {
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
            'username' => 'required|max_length[100]',
            'password' => 'required|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('validationErrors', $this->validator->getErrors());
        }

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');
        $account  = db_connect()->table('accounts')
            ->where('username', $username)
            ->get()
            ->getRowArray();

        if ($account === null || ! password_verify($password, $account['password'])) {
            return redirect()->back()->withInput()->with('error', 'The username or password is incorrect.');
        }

        $role = $account['role'];
        if (! in_array($role, ['Admin', 'Registrar', 'Instructor', 'Student'], true)) {
            return redirect()->back()->withInput()->with('error', 'This account does not have a supported role.');
        }

        if ($role === 'Student' && ! db_connect()->table('students')->where('student_id', $account['role_id'])->countAllResults()) {
            return redirect()->back()->withInput()->with('error', 'This student account is not linked to a student record.');
        }

        if ($role === 'Instructor' && ! db_connect()->table('instructors')->where('instructor_id', $account['role_id'])->countAllResults()) {
            return redirect()->back()->withInput()->with('error', 'This instructor account is not linked to an instructor record.');
        }

        session()->regenerate(true);
        session()->set('user', [
            'account_id' => (int) $account['account_id'],
            'username'   => $account['username'],
            'role'       => $role,
            'role_id'    => $account['role_id'] === null ? null : (int) $account['role_id'],
        ]);

        return redirect()->to(site_url('dashboard'));
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(site_url('login'))->with('message', 'You have been signed out.');
    }
}
