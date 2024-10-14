<?php

return [
    'models' => [
        'menu' => [
            'presenter' => \Modules\Menu\Transformers\MenuPresenter::class,
            'resource_url' => 'menu',
            'translatable' => ['name']
        ],
    ]
];
