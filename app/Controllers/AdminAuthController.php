<?php

namespace App\Controllers;

class AdminAuthController extends BaseController
{
    public function login()
    {
        if (auth()->loggedIn()) {
            if (auth()->user()->inGroup('admin')) {
                return redirect()->to(site_url('admin/dashboard'));
            }

            return redirect()->to(site_url('dashboard'))
                ->with('error', 'Sign out of your current account before using administrator sign-in.');
        }

        return view('auth/admin_login', [
            'username' => old('username', ''),
        ]);
    }

    public function authenticate()
    {
        if (auth()->loggedIn()) {
            return redirect()->to(site_url('dashboard'))
                ->with('error', 'Sign out of your current account before using administrator sign-in.');
        }

        if (! $this->validate([
            'username' => 'required|max_length[30]',
            'password' => 'required|max_length[255]',
        ])) {
            return redirect()->back()->with('error', 'Enter a valid administrator username and password.');
        }

        $result = auth()->attempt([
            'username' => trim((string) $this->request->getPost('username')),
            'password' => (string) $this->request->getPost('password'),
        ]);

        if (! $result->isOK()) {
            return redirect()->back()->with('error', 'The administrator username or password is incorrect.');
        }

        if (! auth()->user()->inGroup('admin')) {
            auth()->logout();

            return redirect()->to(site_url('admin'))
                ->with('error', 'This sign-in page is for administrator accounts only.');
        }

        return redirect()->to(site_url('admin/dashboard'));
    }
}
