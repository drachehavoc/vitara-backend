<?php

const DS = DIRECTORY_SEPARATOR;
const ROOT = __DIR__ . DS;
const HOME = ROOT . 'system' . DS;

require_once HOME . 'Core' . DS . 'ErrorHandler.php';
require_once HOME . 'Core' . DS . 'Autoload.php';

\Core\ErrorHandler
    ::getInstance();

\Core\Autoload
    ::getInstance();

\Core\Config
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

\Core\Gate
    ::getInstance();

\Core\ResponseHandler
    ::getInstance()
    ->response();