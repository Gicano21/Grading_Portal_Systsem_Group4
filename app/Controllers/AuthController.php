<?php

namespace App\Controllers;

use App\Models\UserModel;

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
            return redirect()->back()->with('validationErrors', $this->validator->getErrors());
        }

        $result = auth()->attempt([
            'username' => trim((string) $this->request->getPost('username')),
            'password' => (string) $this->request->getPost('password'),
        ]);

        if (! $result->isOK()) {
            return redirect()->back()->with('error', 'The username or password is incorrect.');
        }

        $user = auth()->user();

        if ($user->inGroup('admin')) {
            auth()->logout();

            return redirect()->to(site_url('admin'))->with('error', 'Use the administrator sign-in page.');
        }

        if (! $user->inGroup('registrar', 'instructor', 'student')) {
            auth()->logout();

            return redirect()->back()->with('error', 'This account does not have a supported role.');
        }

        if ($user->inGroup('student') && ! db_connect()->table('students')->where('student_id', $user->role_id)->countAllResults()) {
            auth()->logout();

            return redirect()->back()->with('error', 'This student account is not linked to a student record.');
        }

        if ($user->inGroup('instructor') && ! db_connect()->table('instructors')->where('instructor_id', $user->role_id)->countAllResults()) {
            auth()->logout();

            return redirect()->back()->with('error', 'This instructor account is not linked to an instructor record.');
        }

        return redirect()->to(site_url('dashboard'));
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->to(site_url('login'))->with('message', 'You have been signed out.');
    }

    public function password()
    {
        return view('auth/change_password');
    }

    public function updatePassword()
    {
        if (! $this->validate([
            'current_password' => 'required|max_length[255]',
            'password'         => 'required|min_length[8]|max_length[255]',
            'password_confirm' => 'required|matches[password]',
        ])) {
            return redirect()->back()->with('error', 'Enter your current password and confirm a new password of at least 8 characters.');
        }

        $user = auth()->user();
        $identity = $user->getEmailIdentity();
        $currentPassword = (string) $this->request->getPost('current_password');

        if ($identity === null || $identity->secret2 === null || ! service('passwords')->verify($currentPassword, $identity->secret2)) {
            return redirect()->back()->with('error', 'Your current password is incorrect.');
        }

        $newPassword = (string) $this->request->getPost('password');
        $passwordCheck = service('passwords')->check($newPassword, $user);
        if (! $passwordCheck->isOK()) {
            return redirect()->back()->with('error', $passwordCheck->reason());
        }

        $user->setPassword($newPassword);
        model(UserModel::class)->save($user);

        return redirect()->to(site_url('account/password'))->with('message', 'Your password has been changed.');
    }
}
