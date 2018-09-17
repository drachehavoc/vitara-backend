<?php

namespace system;

use system\util\Path;

class Navigator extends Inject
{
    protected $ambue;
    
    function __construct() 
    {
        Parent::__construct();
        Path::requireFunction($this, $this, $this->ambue->runtime->application->routes)();
    }

    public function regex($destination, $function, $continue = false) 
    {
        preg_match($destination, $this->ambue->request->destination, $matches);
        if(!empty($matches)) {
            $this->returns[] = \Closure::bind($function, $this->ambue, null)();
            $continue or die(); 
        }
        return $this;
    }
}