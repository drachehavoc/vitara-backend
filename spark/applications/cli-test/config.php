<?php return function () {
    Autoload
        ::getInstance()
        ->addPath(__DIR__);
    $x = new Helper\Vai();
    return 'esse é o retorno de uma função de rota ' . $x->val();
};