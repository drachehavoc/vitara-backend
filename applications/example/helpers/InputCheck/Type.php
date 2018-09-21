<?php

namespace helper\InputCheck;

class Type
{
    protected $value = null;
    protected $errors = [];
    protected $parameters = [];

    /**
     * valor recebido no input
     */

    function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * define os parametros customizados do input
     */
    
    function setParameters(... $parameters)
    {
        $this->paramaters = $parameters;
    }

    /**
     * formata para retornar para o banco de dados
     */
    
    function getFormatted()
    {
        return $this->value;
    }

    /**
     * retorna a lista de error do campo 
     */

    function getErrors()
    {
        return empty($this->errors) ? null : $this->errors;
    }

    /**
     * se retornar false não é adicionado a lista de inputs verificados
     */

    function isValid()
    {
        return true;
    }
}