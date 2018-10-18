<?php

namespace Helper\Relational\Map;

class Check
{   
    static function columnAlias(string $column)
    {
        if (!preg_match("/^[a-zA-Z_][a-zA-Z0-9 _-]*$/", $column))
            throw new \Exception("invalid column alias: '{$column}'");
        return $column;
    }
}