<?php

namespace App\Models;

use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
    protected $allowedFields = [
        'username',
        'status',
        'status_message',
        'active',
        'last_active',
        'role_id',
    ];

    public function findByCredentials(array $credentials): ?User
    {
        $user = parent::findByCredentials($credentials);

        if ($user !== null && isset($credentials['username'])) {
            // Shield stores password hashes in its email/password identity.
            $user->password_hash = $user->getEmailIdentity()?->secret2;
        }

        return $user;
    }
}
