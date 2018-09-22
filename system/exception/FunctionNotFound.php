<?php 

namespace system\exception;

class FunctionNotFound extends \Exception 
{
    function __construct($file)
    {
        $this->message = "O arquivo `$file` não retorna uma função.";
    }
}