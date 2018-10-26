<?php return [
    // 'production' => true,
    'routes' => [
        '{^POST?/auth}' => 'access-create-token',
        // '{^POST?/auth}' => function() { return "aaaaaaaaaaaaaaaa"; },
    
    // CHECK PERMISSIONS
        '{^GET?/check/(?<token>.+?)/(?<slug>.+)}' => 'access-check-permission',
        '{^GET?/check/(?<token>.+)}' => 'access-check-permission',
    
    // UPDATE
        '{^GET?/auth/(?<token>.+)}' => 'access-update-token',

    // DELETE
        '{^DELETE?/auth/(?<token>.+)}' => 'access-delete-token',
    ]
];

// namespace APPLICATION;


// function routea() {
    
// }

// const NAME = "Access";
// const PRODUCTION = false;
// const LANGUAGE = 'pt-br';
// const TIMEZONE = 'America/Sao_Paulo';
// const HELPERS = HOME . 'helpers' . DS;
// // const LOGS = HOME . 'logs' . DS;
// const ROUTES = [
//     // CREATE OR UPDATE
//     '{^POST?/auth}' => 'access-create-token',
    
//     // CHECK PERMISSIONS
//     '{^GET?/check/(?<token>.+?)/(?<slug>.+)}' => 'access-check-permission',
//     '{^GET?/check/(?<token>.+)}' => 'access-check-permission',
    
//     // UPDATE
//     '{^GET?/auth/(?<token>.+)}' => 'access-update-token',
//     // '{^GET?/auth/(?<token>.+)}' => require "x",

//     // DELETE
//     '{^DELETE?/auth/(?<token>.+)}' => 'access-delete-token',
// ];