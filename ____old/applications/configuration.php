<?php return [
    // 'production' => true,
    'gates' => [
        '127.0.0.1' => 'example',
        'localhost' => 'example',
        
        'access.localhost' => function () {
            return Helper\Access::route($this);
        },
        // 'access.localhost' => 'access',
    ]
];