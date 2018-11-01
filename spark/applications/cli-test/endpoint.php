<?php return function () {

    $inp = Helper\Input::getInstance();

    return $inp->filter([
        "apelido" => Helper\Input\Filter::any(false),
        "nome" => Helper\Input\Filter::any()
    ]);

    // ------------------------------------------------------------------------- 

    Helper\Route::createInstance()
        ->add('{GET/.*}', 'caraiiiiii')
        ->add('{GET/bbb}', function () {
            return 'function baby';
        })
        ->go(true);

    return '************* criar uma espécie de prevent default';

    // -------------------------------------------------------------------------

    Autoload
        ::getInstance()
        ->addPath(__DIR__);

    $x = new Helper\Vai();

    return $x->val();

    // -------------------------------------------------------------------------

    return 'esse é o retorno de uma função de rota ';
};