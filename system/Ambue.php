<?php

namespace system;

require __DIR__ . '/php/configure.php';
require __DIR__ . '/php/autoload.php';

class Ambue {
    // just for the name
}

$ambue = new Ambue;

new Request(); 
new Runtime(); 
new Helper(); 
new Navigator();

// print_r($ambue);