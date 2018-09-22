<?php 

namespace system\exception;

class AttributeNotExists extends \Exception 
{
    function __construct($attribute, $classname)
    {
        $this->message = "O atributo `$attribute` não existe na classe `$classname`.";
    }
}