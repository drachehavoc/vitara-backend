<?php

namespace Helper\Input;

class Filter
{
    static function __callStatic($name, $arguments)
    {
        $typeClass = __NAMESPACE__."\\Types\Type".ucfirst($name);
        return new $typeClass(... $arguments);
    }
}