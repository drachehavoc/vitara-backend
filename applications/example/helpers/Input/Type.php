<?php

namespace helper\Input;

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
     * formata para retornar para o banco de dados
     */
    
    function getFormatted()
    {
        return $this->value;
    }

    /**
     * se retornar false não é adicionado a lista de inputs verificados
     */

    function validate()
    {
        throw new \Exception('xxxxxxxxx');
    }
}