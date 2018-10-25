<?php 

namespace Helper\Prepare;

class Type
{
    protected $value;
    protected $params;

    function __construct($value, ...$params)
    {
        $this->value = $value;
        $this->params = $params;
    }

    function getValue()
    {
        return $this->value;
    }
}