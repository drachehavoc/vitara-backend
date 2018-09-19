<?php

namespace system;

use system\util\Path;

class Helper extends Inject 
{
    private $loaded = [];

    // function __construct() 
    // {
    //     Parent::__construct();
    // }

    function load($name, $alias = null)
    {
        $alias = $alias ?? $name;
        
        if (isset($this->loaded[$alias]))
            return $this->loaded[$alias];
        
        $file = DIRECTORY_SEPARATOR . "{$name}.php"; 
        $file = Path::firstFound(
            $this->ambue->runtime->application->helpers . $file,
            $this->ambue->runtime->helpers . $file
        );

        $req = Path::require($file);

        if (is_callable($req))
            return $this->loaded[$alias] = \Closure::bind($req, $this);

        return $req;
    }

    function __call($name, $arguments)
    {
        if (!isset($this->loaded[$name]))
            throw new \system\exception\HelperNotLoaded($name);
        return ($this->loaded[$name])(... $arguments);
    }

    function __get($alias)
    {
        return $this->loaded[$alias] ?? null;
    }
}