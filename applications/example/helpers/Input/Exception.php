<?php

namespace helper\Input;

class Exception extends \Exception
{
    function __construct($nulls, $typeExceptions)
    {
        $this->message = 'Invalid input data.';
        $this->code = (Object)[
            'nulls' => $nulls,
            'custom' => $typeExceptions
        ];
    }
}