<?php return [
    // 'applications' => 'outra pasta para conter as aplicações',
    'paths' => [
        // 'cli-test' => function () {
        //     return 'eu sou o retorno de uma função';
        // },

        // 'cli-test' => 'cli-test/config.php',

        'cli-test' => [
            'cli-test/config.php',
            function () {
                return 'eu sou o retorno de uma função';
            }
        ],
    ]
];