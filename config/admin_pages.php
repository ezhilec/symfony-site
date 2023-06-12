<?php

return [
    [
        'name' => 'Asset management',
        'icon' => 'bi bi-buildings',
        'route' => 'app_admin_index',
        'children' => [
            [
                'name' => 'Pages',
                'icon' => 'bi bi-file-earmark',
                'route' => 'app_admin_page_index',
            ],
            [
                'name' => 'Product categories',
                'icon' => 'bi bi-diagram-3',
                'route' => 'app_admin_product_category_index',
            ],
            [
                'name' => 'Products',
                'icon' => 'bi bi-box-seam',
                'route' => 'app_admin_product_index',
            ],
        ],
    ],
    [
        'name' => 'Users',
        'icon' => 'bi bi-person',
        'route' => 'app_admin_user_index',
    ],
];