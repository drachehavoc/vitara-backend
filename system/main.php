<?php

namespace SYSTEM;

const HOME = __DIR__ . DS;

$root['step'] = 'main_2';

namespace Core;

require "Core\Autoload.php";

Autoload::getInstance();
ErrorHandler::getInstance();
System::getInstance();