<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $user = session('user');

        if (! is_array($user)) {
            return redirect()->to(site_url('login'))->with('error', 'Please sign in to continue.');
        }

        if ($arguments !== null && ! in_array($user['role'] ?? '', $arguments, true)) {
            return redirect()->to(site_url('dashboard'))->with('error', 'You do not have permission to access that page.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
