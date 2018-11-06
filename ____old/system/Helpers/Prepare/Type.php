<?php 

namespace Helper\Prepare;

class Type
{
    protected $name;
    protected $alias;
    protected $value;
    protected $params;

    function __construct($name, $alias, $value, ...$params)
    {
        $this->name = $name;
        $this->alias = $alias;
        $this->value = $value;
        $this->params = $params;
    }

    function getValue()
    {
        return $this->value;
    }
}