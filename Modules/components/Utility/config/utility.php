<?php

return [
    'models' => [
        'rating' => [
        ],
        'wishlist' => [
            'resource_url' => 'utilities/wishlist',
        ],
        'location' => [
            'presenter' => \Modules\Components\Utility\Transformers\Address\LocationPresenter::class,
            'resource_url' => 'utilities/address/locations',
        ],
        'tag' => [
            'presenter' => \Modules\Components\Utility\Transformers\Tag\TagPresenter::class,
            'resource_url' => 'utilities/tags',
            'translatable' => ['name']
        ],
        'category' => [
            'presenter' => \Modules\Components\Utility\Transformers\Category\CategoryPresenter::class,
            'resource_url' => 'utilities/categories',
            'default_image' => 'assets/modules/images/default_product_image.png',
            'translatable' => ['name']
        ],
        'attribute' => [
            'presenter' => \Modules\Components\Utility\Transformers\Category\AttributePresenter::class,
            'resource_url' => 'utilities/attributes',
        ],
        'model_option' => [
        ]
    ]
];
