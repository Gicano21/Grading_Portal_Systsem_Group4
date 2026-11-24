<?php

namespace Config;

use App\Models\UserModel;
use CodeIgniter\Shield\Config\Auth as ShieldAuth;

class Auth extends ShieldAuth
{
    public bool $allowRegistration = false;

    public bool $allowMagicLinkLogins = false;

    public array $validFields = ['username'];

    public string $userProvider = UserModel::class;
}
