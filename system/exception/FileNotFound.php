<?php 

namespace system\exception;

class FileNotFound extends \Exception 
{
    function __construct($file)
    {
        $this->message = "O arquivo `$file` não foi encontrado.";
    }
}