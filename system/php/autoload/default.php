<?php

spl_autoload_register(function($namespace) {
    $file = AMBUE_DIR . DIRECTORY_SEPARATOR . $namespace . '.php';

    if (file_exists($file))
        return require_once $file;
    
    global $ambue;

    $special = (require "special-cases.php")($namespace);

    if ($special->requireOnce('helper', 
        $ambue->runtime->application->helpers,
        $ambue->runtime->helpers
    )) return;

    throw new Exception("Class file not found! Class Name: $namespace, File: $file");
});