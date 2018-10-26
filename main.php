<?php

// ROOT GLOBAL OBJECT

$root = [
    'step' => 'main_1',
    'context' => (Object)[
        'path' => $_SERVER['REQUEST_METHOD'] . ($_SERVER['PATH_INFO'] ?? $_SERVER['PHP_SELF']),
        'query' => (Object)$_GET,
        'payload' => json_decode(file_get_contents('php://input')),
        'matches' => null // populate on execute Core\System::route
    ],
    'helpers' => [], // populate on instace Core\System,Core\System::gate,Core\System::route
    'override' => [], // override with last method executed in this order Core\System,Core\System::gate,Core\System::route
    'system' => null, // populate on instace Core\System
    'gate' => null, // populate on execute Core\System::gate
    'route' => null, // populate on execute Core\System::route
];

// HOST NAME

define('HOST', $_SERVER['HTTP_HOST']);

// FOR SHORTNER

const DS = DIRECTORY_SEPARATOR;

// HOME OF WHOLE SYSTEM

const HOME = __DIR__ . DS;

// DEFAULT APPLICATION FOLDER

const APPLICATIONS = 'applications';

// DEFAULT CONFIGURATIONS FILES NAMES

const CONFIGURATION = 'configuration.php';

// BEGIN THE JOKE

require_once 'system/main.php';