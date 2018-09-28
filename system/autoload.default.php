<?php return function ($namespace) {
    $file = \SYSTEM\HOME . DIRECTORY_SEPARATOR . "{$namespace}.php";

    if (file_exists($file))
        return require_once $file;   
        
    throw new Exception("Class file not found! Class Name: $namespace, File: $file");
};