<?php

namespace helper;

class InputCheck 
{
    private $ambue;
    private $query = [];
    private $body = [];
    private $errors = [];

    function __construct()
    {
        global $ambue;
        $this->ambue = $ambue;
    }

    private function errorMessage($prefix, $columnName, $msg)
    {
        $this->errors[$prefix][$columnName][] = $msg;
        return $this;
    }

    function check($prefix, &$saveTo, $checkOn, $name, $nullable, $type)
    {
        $value = null;
        $checkOn = (Object)$checkOn;

        if (!($type instanceof InputCheck\Type))
            throw new Exception('tratar as mensagens de erro de ImputCheck::query');
        
        if ( isset($checkOn->{$name}) ) 
            $value = $checkOn->{$name};

        if (is_null($value) && !$nullable)
            return $this->errorMessage($prefix, $name, "ouoeruoeruoerueorueroueroueroureoeeoruoeru `{$name}` can't be null");
        
        $type->setValue($value);
        $saveTo['name'] = $type->getFormated();
        
        return $this;
    }

    function query($name, $nullable, $type)
    {
        return $this->check(
            "query",
            $this->query,
            $this->ambue->request->search, 
            $name, 
            $nullable, 
            $type);
    }

    function body($name, $nullable, $type)
    {
        return $this->check(
            "body",
            $this->body,
            $this->ambue->request->body, 
            $name, 
            $nullable, 
            $type);
    }

    function __get($name)
    {
        switch ($name) {
            case 'query'  : return $this->query;
            case 'body'   : return $this->body;
            case 'errors' : return $this->errors;
        }

        throw new \system\exception\AttributeNotExists($name);
    }
}