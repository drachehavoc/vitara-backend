<?php

const DS = DIRECTORY_SEPARATOR;

const ROOT = __DIR__
    . DS;

const HOME = __DIR__
    . DS
    . 'system'
    . DS;

require_once HOME
    . 'ErrorHandler.php';

require_once HOME
    . 'Autoload.php';

ErrorHandler
    ::getInstance();

Autoload
    ::getInstance();

Config
    ::getInstance()
    ->set([
        'production' => false,
        'gates' => ROOT . 'applications' . DS . 'gates.php',
        'applications' => ROOT . 'applications' . DS
    ])
    ->load(ROOT . 'config.php')
    ->set([
        'host' => $_SERVER['HTTP_HOST'],
    ]);

Gate
    ::getInstance();

ResponseHandler
    ::getInstance()
    ->response();