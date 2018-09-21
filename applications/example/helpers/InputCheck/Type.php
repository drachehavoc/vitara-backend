<?php

namespace helper\InputCheck;

class Type
{
    protected $value = null;
    
    function setValue($value)
    {
        $this->value = $value;
    }

    function getFormated()
    {
        return "XXXX {$this->value} XXXX";
    }
}