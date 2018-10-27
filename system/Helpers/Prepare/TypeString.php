<?php 

namespace Helper\Prepare;

class TypeString extends Type
{
    private $notnull = false;

    function __construct($name, $alias, $value, ...$params)
    {
        parent::__construct($name, $alias, $value);
        $this->notnull = $params[0] ?? false;
    }

    function getValue()
    {
        if ($this->notnull && is_null($this->value)) 
            throw new \Exception("`{$this->name}`, não informado");

        if (!is_string(($this->value)))
            throw new \Exception("`{$this->name}`, deve ser uma string");


        return $this->value;
    }
}