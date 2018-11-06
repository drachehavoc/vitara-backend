<?php

namespace Core\Exception;

class FobiddenType extends HttpException
{

    function __construct($parameterName, $parameterValue, ...$types)
    {
        $trace = $this->getTrace()[0];
        print_r($trace);
        $function = "{$trace['class']}::{$trace['function']}";
        $given = gettype($parameterValue);
        if ($parameterValue == 'object')
            $given = get_class($parameterValue);
        $this->message = "`{$function}` parameter `$parameterName` must be of the following types: " . implode(', ', $types) . ", `{$given}` was given";
    }
}