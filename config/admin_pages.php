<?php

return [
    [
        'name' => 'Asset management',
        'icon' => 'bi bi-buildings',
        'route' => 'app_admin_index',
        'children' => [
            [
                'name' => 'Pages',
                'icon' => '',
                'route' => 'app_admin_page_index',
            ],
            [
                'name' => 'Products',
                'icon' => '',
                'route' => '',
            ],
            [
                'name' => 'Product categories',
                'icon' => '',
                'route' => 'app_admin_product_category_index',
            ],
        ],
    ],
    [
        'name' => 'Users',
        'icon' => 'bi bi-person',
        'route' => 'app_admin_user_index',
    ],
];