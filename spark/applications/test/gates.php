<?php return [
    // 'production' => true,
    // 'applications' => 'outra pasta para conter as aplicações',
    'paths' => [
        // 'cli-test' => function () {
        //     return 'eu sou o retorno de uma função';
        // },

        // 'cli-test' => 'cli-test/config.php',

        // 'access.localhost' => [
        //     'cli-test/endpoint.php',
        //     'cli-test/x/config2.php',
        //     function () {
        //         // Helper\Route::createInstance()
        //         //     ->add('{GET/.*}', 'caraiiiiii')
        //         //     ->add('{GET/bbb}', 'bbb')
        //         //     ->go();
        //         return 'eu sou o retorno de uma função';
        //     }
        // ],

        'access.localhost' => function () {
            Core\Config
                ::getInstance()
                ->load(__DIR__ . DS . 'database.config.php', 'database');

            // print_r(
            //     Core\Config
            //     ::getInstance()
            //     ->get('database')
            // );
                
            $x = new Helper\PDO();
            $s = $x->prepare('show tables');
            $s->execute();
            return 1000;
        }
    ]
];