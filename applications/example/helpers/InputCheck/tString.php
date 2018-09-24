<?php

namespace helper\InputCheck;

class tString extends Type
{

    function __construct()
    {
        $this->errors[] = 'erro teste';
    }
    
    // function getFormatted()
    // {
    //     return "XXX {$this->value} XXXX";
    // }
}