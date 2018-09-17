<?php

namespace system;

class Inject 
{
    protected $ambue;

    function __construct() 
    {
        global $ambue;
        $className = (new \ReflectionClass($this))->getShortName(); 
        $className = lcfirst($className);
        $ambue->{$className} = $this;
        $this->ambue = $ambue;
    }
}