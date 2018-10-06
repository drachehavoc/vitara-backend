<?php

namespace Core;

class Autoload
{
    static private $instance = null;

    private $list = [];

    static function getInstance()
    {
        return Self::$instance 
            ? Self::$instance 
            : Self::$instance = new Self(); 
    }
    
    private function __construct(){
        spl_autoload_register([$this, 'trigger']);
    }

    private function common ($namespace) 
    {
        $file = \SYSTEM\HOME . DIRECTORY_SEPARATOR . "{$namespace}.php";
        if (file_exists($file)) return require_once $file;   
        throw new \Core\Exception\ClassFileNotFound("Class file not found! Class Name: $namespace, File: $file");
    }

    private function helper ($namespace) 
    {
        $namespace = ltrim($namespace, "Helper\\");
        $file = \Core\Util\Path::findIn("{$namespace}.php", \APPLICATION\HELPERS, \SYSTEM\HELPERS);
        if ($file) return require_once $file;     
        throw new \Core\Exception\ClassFileNotFound("Class file not found! Class Name: $namespace, File: $file [[[[ADICIONAR TODOS OS CAMINHÕES NÃO ENCONTRADOS A ESTE EXCEPTION]]]]");
    }

    private function trigger ($namespace)
    {
        if (isset($this->list[$namespace])) 
            return;
        
        $this->list[] = $namespace;

        switch (true)
        {
            case strpos($namespace, 'Helper') === 0: 
                return $this->helper($namespace);
                
            default:
                return $this->common($namespace);
        }
    }
}