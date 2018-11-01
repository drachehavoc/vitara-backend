<?php

namespace Helper\Input\Types;

class Type
{
    protected $nullable = true;
    protected $value = null;
    protected $name = null;
    protected $alias = null;

    function __construct($nullable = true, $alias = null)
    {
        $this->nullable = $nullable;
        $this->alias = $alias;
    }

    function setName($name)
    {
        $this->name = $name;
    }

    function getName()
    {
        if ($this->alias)
            return $this->alias;
        return $this->name;
    }

    function setValue($value)
    {
        $this->value = $value;
    }

    function getValue()
    {
        return $this->value;
    }

    function mount()
    {
        // ...
    }

    function checkNull()
    {
        if (!$this->nullable && $this->value === null)
            throw new \Exception("O campo `{$this->name}`, não pode ser nulo");
    }
}