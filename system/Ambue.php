<?php

namespace system;

$__ELIPSED = microtime(true);

require __DIR__ . '/php/configure.php';
require __DIR__ . '/php/autoload/default.php';

class Ambue {/* just for the name */};
$ambue = new Ambue;
new Request(); 
new Runtime(); 
new Navigator();