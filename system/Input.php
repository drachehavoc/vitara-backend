<?php

namespace system;

use system\util\Path;

class Input extends Inject 
{
    private $loaded = [];
    private $search = [];
    private $body   = [];
    private $errors = [];

    // function __construct() 
    // {
    //     Parent::__construct();
    // }

    private function load($name, $context)
    {
        if (isset($this->loaded[$name]))
            return \Closure::bind($this->loaded[$name], $context);
        
        $file = DIRECTORY_SEPARATOR . "{$name}.php"; 
        $file = Path::firstFound(
            $this->ambue->runtime->application->types . $file,
            $this->ambue->runtime->types . $file
        );

        $req = Path::require($file);

        if (is_callable($req))
            return $this->loaded[$name] = \Closure::bind($req, $context);

        throw new \system\exception\InconsistentType($name);
    }

    private function errorMessage($prefix, $columnName, $msg)
    {
        $this->errors[$prefix][$columnName][] = $msg;
        return $this;
    }

    private function check($prefix, &$changer, $base, $columnName, $nullable, $type, ... $params)
    {
        $value = null;
        $base = (Object)$base;

        if ( isset($base->{$columnName}) ) 
            $value = $base->{$columnName};
        
        if (is_null($value) && !$nullable)
            return $this->errorMessage($prefix, $columnName, "ouoeruoeruoerueorueroueroueroureoeeoruoeru `{$columnName}` can't be null");
        
        $type = $this->load($type, (Object)[
            'value'  => $value,
            'search' => $base
        ]);

        $changer[$columnName] = $type(... $params);

        return $this;
    }
    
    function checkSearch($columnName, $nullable, $type, ... $param)
    {
        return $this->check(
            'search',
            $this->search, 
            $this->ambue->request->search, 
            $columnName, 
            $nullable, 
            $type, 
            ... $param);
    }
    
    function checkBody($columnName, $nullable, $type, ... $param)
    {
        return $this->check(
            'body',
            $this->body, 
            $this->ambue->request->body, 
            $columnName, 
            $nullable,
            $type, 
            ... $param);
    }

    function throw() 
    {
        print_r($this->errors);
    }

    function __get($key)
    {
        switch ($key) {
            case 'search' : return $this->search;
            case 'body'   : return $this->body;
        }
    }
}