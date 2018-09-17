<?php

namespace system;

class Request extends Inject 
{
    function __construct() 
    {
        Parent::__construct();
        $this->method      = $_SERVER['REQUEST_METHOD'];
        $this->query       = (Object)$_GET;
        $this->path        = $_SERVER['PATH_INFO'] ?? $_SERVER['PHP_SELF'];
        $this->destination = "{$this->method}{$this->path}";
        $this->body        = json_decode(file_get_contents('php://input'));
    }
}