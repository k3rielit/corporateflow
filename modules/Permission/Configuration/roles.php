<?php

return [

    'superadmin' => [],

    'admin' => [
        'permissions' => [
            'access panel admin',
        ],
        'model_permissions' => [
            \App\Models\User::class => ['viewAny', 'view', 'create', 'update', 'delete', 'restore'],
        ],
    ],

    'user' => [
        'permissions' => [
            'access panel user',
        ],
    ],

];
