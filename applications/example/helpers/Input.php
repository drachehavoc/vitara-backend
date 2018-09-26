<?php

namespace helper;

class Input
{
    private $ambue;
    private $input;
    private $result;
    private $nulls = [];
    private $exceptions = [];

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
            return $this;
        }
        
        try {
            $type->setValue($value);
            $value = $type->getFormatted();
            $type->validate();
            $this->result[$name] = $value; 
        } catch (\Exception $e) {
            $this->exceptions[$name]['message'] = $e->getMessage();
            $this->exceptions[$name]['detail'] = $e->getCode();
            $this->exceptions[$name]['type'] = get_class($e);
        }

        return $this;
    }

    private function getAsArray()
    {
        if (!empty($this->nulls) || !empty($this->exceptions))
            throw new \helper\input\Exception($this->nulls, $this->exceptions);
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