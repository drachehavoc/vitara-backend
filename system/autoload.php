<?php

namespace autoload;

function autoload ($namespace)
{
    switch(true)
    {
        case strpos($namespace, 'Helper') === 0: 
            return (include 'autoload.helper.php')($namespace);

        default:
            return (include 'autoload.default.php')($namespace);
    }
}

spl_autoload_register('autoload\autoload');