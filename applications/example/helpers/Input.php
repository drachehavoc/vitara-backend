<?php

namespace helper;

class Input
{
    private $ambue;
    private $input;
    private $result;
    private $nulls = [];

    function __construct(Array $input)
    {
        global $ambue;
        $this->ambue = $ambue;
        $this->input = $input;
    }
    
    function check($name, $defaultValue, \helper\Input\Type $type)
    {
        $value = $defaultValue;
        
        if (isset($this->input[$name])) {
            $value = $this->input[$name];
        }
            
        if (is_null($value)) {
            $this->nulls[] = $name;
            return;
        }
        
        $this->result[$name] = $value;
        return $this;
    }

    private function getAsArray()
    {
        if (!empty($this->nulls))
            throw new \helper\input\Exception($this->nulls);
        return $this->result;
    }

    function __get($name)
    {
        switch ($name) {
            case 'array': return $this->getAsArray();
        }

        throw new \system\exception\AttributeNotExists($name, __CLASS__);
    }
}