<?php

namespace system;

class Request extends Inject 
{
    function __construct() 
    {
        Parent::__construct();
        $this->method      = $_SERVER['REQUEST_METHOD'];
        $this->query       = $_GET;
        $this->path        = $_SERVER['PATH_INFO'] ?? $_SERVER['PHP_SELF'];
        $this->destination = "{$this->method}{$this->path}";
        $this->matches     = null; // setted by navigator
        $this->search      = null; // setted by navigator
        $this->body        = json_decode(file_get_contents('php://input'));
    }
}