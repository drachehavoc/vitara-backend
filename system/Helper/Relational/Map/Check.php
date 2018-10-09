<?php

namespace Helper\Relational\Map;

class Check
{
    static function tableName(string $table)
    {
        if (!preg_match("/^[a-zA-Z_][a-zA-Z0-9_]*$/", $table))
            throw new \Exception("invalid table name: '{$table}'");
        return $table;
    }
    
    static function columnName(string $column)
    {
        if (!preg_match("/^[a-zA-Z_][a-zA-Z0-9_]*$/", $column))
            throw new \Exception("invalid column name: '{$column}'");
        return $column;
    }
    
    static function columnAlias(string $column)
    {
        if (!preg_match("/^[a-zA-Z_][a-zA-Z0-9 _-]*$/", $column))
            throw new \Exception("invalid column alias: '{$column}'");
        return $column;
    }
}