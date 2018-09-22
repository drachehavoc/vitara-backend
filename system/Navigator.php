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

    public function regex($destination, $function) 
    {
        preg_match($destination, $this->ambue->request->destination, $matches);
        $this->ambue->request->matches = $matches;
        $this->ambue->request->search = array_merge_recursive($matches, $this->ambue->request->query);
        if(!empty($matches)) {
            $ret = \Closure::bind($function, $this->ambue, null)();
            die( json_encode($ret) );
            $continue or die(); 
        }
        return $this;
    }
}