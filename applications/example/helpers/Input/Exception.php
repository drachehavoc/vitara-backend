<?php

namespace helper\Input;

class Exception extends \Exception
{
    function __construct($nulls)
    {
        $this->message = 'Invalid input data.';
        $this->code = (Object)[
            'nulls' => $nulls
        ];
    }
}