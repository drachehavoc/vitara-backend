<?php

namespace system;

use system\util\Path;

class Runtime extends Inject 
{
    function __construct() 
    {
        Parent::__construct();

        $gates     = require AMBUE_FILE_GATES;
        $appName   = $gates[$_SERVER['HTTP_HOST']] ?? AMBUE_DEFAULT_APP;
        $appPath   = Path::resolve(AMBUE_DIR_APPLICATIONS, $appName);

        $this->application = (Object)[
            'name'    => $appName,
            'path'    => $appPath,
            'routes'  => Path::resolve($appPath, 'routes.php'),
            'helpers' => Path::join($appPath, 'helpers')
        ];

        $this->gates   = $gates;
        $this->helpers = Path::resolve(AMBUE_DIR_HELPERS);
    }
}