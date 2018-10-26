<?php return [
    // 'production' => true,
    'gates' => [
        '127.0.0.1' => 'example',
        // 'localhost' => 'example',
        'access.localhost' => function () {
            $access = new Helper\Access();
            return $access->route($this);
        },
        // 'access.localhost' => 'access',
    ]
];