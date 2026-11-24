<?php

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    public string $defaultGroup = 'student';

    public array $groups = [
        'admin' => [
            'title'       => 'Administrator',
            'description' => 'Manages grades and classes.',
        ],
        'registrar' => [
            'title'       => 'Registrar',
            'description' => 'Manages enrollment and class records.',
        ],
        'instructor' => [
            'title'       => 'Instructor',
            'description' => 'Manages grades for assigned classes.',
        ],
        'student' => [
            'title'       => 'Student',
            'description' => 'Views personal grades.',
        ],
    ];

    public array $permissions = [];

    public array $matrix = [
        'admin'      => [],
        'registrar'  => [],
        'instructor' => [],
        'student'    => [],
    ];
}
