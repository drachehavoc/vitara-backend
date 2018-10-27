<?php

class Autoload
{
    static private $instance = null;

    static function getInstance()
    {
        return Self::$instance ?? Self::$instance = new Self();
    }

    protected function __construct()
    {  
        spl_autoload_register([$this, 'trigger']);
    }

    protected function helper(string $namespace)
    {
        die($namespace);
    }

    protected function trigger($namespace)
    {
        if (strpos($namespace, 'Helper') === 0)
            return $this->helper($namespace);

        require_once HOME . $namespace . '.php';
    }
}