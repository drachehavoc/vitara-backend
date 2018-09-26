<?php

namespace helper;

class InputCheck 
{
    private $ambue;
    private $query = [];
    private $body = [];
    private $errors = [
        'query' => [],
        'body' => []
    ];

    function __construct($arrayInput)
    {
        global $ambue;
        $this->ambue = $ambue;
    }

    function check($prefix, &$saveTo, $checkOn, $name, $type, ... $customParameters)
    {
        if (!($type instanceof InputCheck\Type))
            throw new \system\exception\InconsistentType($name);
        
        $checkOn = (Object)$checkOn;

        $type->setParameters(... $customParameters);
        $type->setValue($checkOn->{$name} ?? null);

        if ($type->isValid())
            $saveTo[$name] = $type->getFormatted();

        if ($errors = $type->getErrors())
            $this->errors[$prefix][$name] = $errors;
        
        return $this;
    }

    function query($name, $type, ... $parameters)
    {
        return $this->check(
            "query", $this->query, $this->ambue->request->search, 
            $name, $type, ... $parameters);
    }

    function body($name, $type, ... $parameters)
    {
        return $this->check(
            "body", $this->body, $this->ambue->request->body, 
            $name, $type, ... $parameters);
    }

    function __get($name)
    {
        switch ($name) {
            case 'query'  : return $this->query;
            case 'body'   : return $this->body;
            case 'errors' : return $this->errors;
        }

        throw new \system\exception\AttributeNotExists($name, __CLASS__);
    }
}