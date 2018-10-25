<?php

namespace SYSTEM;

const HOME = __DIR__ . DS;

namespace Core;

require "Core\Autoload.php";

Autoload::getInstance();
ErrorHandler::getInstance();
System::getInstance();


// print_r(Util\Path::loadArray(__DIR__ . DS . 'delme.php', [
//     "x" => [
//         "k" => 12,
//         "h" => 12,
//     ]
// ]));

// $root = (Object)[];

// $root->system = (Object)[];
// $root->system->home = __DIR__ . DS; 
// $root->system->helpers = $root->system->home . 'Helper' . DS;

// //
// const HELPERS = HOME . 'Helper' . DS;

// //

// namespace APPLICATIONS;

// const HOME = HOME . 'applications' . DS;
// require_once HOME . 'configuration.php';

// //

// namespace APPLICATION;

// if (!array_key_exists(HOST, \APPLICATIONS\GATES)) {
//     http_response_code(404);
//     die("Unknown application `" . HOST . "`.");
// }
// const HOME = \APPLICATIONS\HOME . \APPLICATIONS\GATES[HOST] . DS;
// require_once HOME . 'configuration.php';

// require "Core\Autoload.php";

// //

// namespace Core;

// Autoload::getInstance();
// ErrorHandler::getInstance();
// System::getInstance();