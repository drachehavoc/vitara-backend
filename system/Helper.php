<?php

namespace system;

use system\util\Path;

class Helper extends Inject 
{
    private $loaded = [];

    function __construct() 
    {
        Parent::__construct();
    }

    public function load($name, $alias = null)
    {
        $file = DIRECTORY_SEPARATOR . "{$name}.php"; 
        $file = Path::firstFound(
            $this->ambue->runtime->application->helpers . $file,
            $this->ambue->runtime->helpers . $file
        );
        return $this->loaded[$alias ?? $name] = Path::require($file);
    }

    public function __call($name, $arguments)
    {
        if (!isset($this->loaded[$name]))
            throw new \system\exception\HelperNotLoaded($name);
        return ($this->loaded[$name])(... $arguments);
    }

    public function __get($alias)
    {
        return $this->loaded[$alias] ?? null;
    }
}