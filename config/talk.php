<?php

return [
    'user' => [
        'model' => 'Modules\User\Models\User'
    ],
    'broadcast' => [
        'enable' => true,
        'app_name' => 'alrabeh-development',
        'pusher' => [
            'app_id'        => "647833",
            'app_key'       => "61bd2bcd93354e06bc00",
            'app_secret'    => "ccc903e01c7eb4dd35a7",
            'options' => [
                'cluster' => 'eu',
                'encrypted' => true

            ]
        ],

    ]
];
