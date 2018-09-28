<?php return function ($namespace) {
    $namespace = ltrim($namespace, "Helper\\");

    $file = \Core\Util\Path::findIn(
        "{$namespace}.php",
        \APPLICATION\HELPERS,
        \SYSTEM\HELPERS
    );

    if ($file)
        return require_once $file;   
        
    throw new Exception("Class file not found! Class Name: $namespace, File: $file [[[[ADICIONAR TODOS OS CAMINHÕES NÃO ENCONTRADOS A ESTE EXCEPTION]]]]");
};