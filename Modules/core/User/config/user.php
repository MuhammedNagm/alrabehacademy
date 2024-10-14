<?php

return [
    'models' => [
        'user' => [
            'presenter' => \Modules\User\Transformers\UserPresenter::class,
            'resource_url' => 'users',
            'default_picture' => 'assets/modules/images/avatars/',
            'translatable' => ['name']
        ],
        'role' => [
            'presenter' => \Modules\User\Transformers\RolePresenter::class,
            'resource_url' => 'roles'
        ],
    ]
];
