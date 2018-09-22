<?php 

namespace system\exception;

class NotTrustedSqlName extends \Exception 
{
    function __construct($name, $value)
    {
        $this->message = "O nome de {$name} não pode ser utilizado como parametro para SQL Query, o valor recebido foi `{$value}`, verifique caracteres inválidos.";
    }
}