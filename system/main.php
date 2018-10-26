<?php

namespace SYSTEM;

$root['step'] = 'main_2';
const HOME = __DIR__ . DS;

namespace Core;

require "Core\Autoload.php";

Autoload::getInstance();
ErrorHandler::getInstance();
System::getInstance();