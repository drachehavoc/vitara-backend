<?php

namespace Core\Exception;

class InaccessibleAttribute extends HttpException
{

    function __construct()
    {
        $trace = $this->getTrace()[0];
        $className = $trace['class'];
        $propertie = implode(', ', $trace['args']);
        $this->message = "Inaccessible propertie `$propertie` in class `$className`";
        $this->code = 500;
    }
}