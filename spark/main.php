<?php

const DS = DIRECTORY_SEPARATOR;
const ROOT = __DIR__ . DS;
const HOME = ROOT . 'system' . DS;

require_once HOME . 'Core' . DS . 'ErrorHandler.php';
require_once HOME . 'Core' . DS . 'Autoload.php';

$scope = (Object)[];
$scope->error = \Core\ErrorHandler::getInstance();
$scope->autoload = \Core\Autoload::getInstance();
$scope->response = \Core\ResponseHandler::getInstance();

$scope->config = \Core\Config::getInstance()
    ->set([
        'production' => false,
        'gates' => ROOT . 'applications' . DS . 'gates.php',
        'applications' => ROOT . 'applications' . DS
    ])
    ->load(ROOT . 'config.php')
    ->set([
        'host' => $_SERVER['HTTP_HOST'],
    ]);

$scope->gate = \Core\Gate::getInstance();
$scope->response->response();