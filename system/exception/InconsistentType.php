<?php 

namespace system\exception;

class InconsistentType extends \Exception 
{
    function __construct($name)
    {
        $this->message = "O parâmetro tipo (\$type) precisa ser um objeto que herde da classe \helper\InputCheck\Type.";
    }
}