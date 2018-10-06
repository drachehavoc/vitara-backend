<?php

namespace Helper\Relational\Map;

function condition(string $col, string $cond, $val)
{
    return new Condition($col, $cond, $val);
}

function checkTableName(string $table)
{
    if (!preg_match("/^[a-zA-Z_][a-zA-Z0-9_]*$/", $table))
        throw new \Exception("invalid table name: '{$table}'");
    return $table;
}

function checkColumnName(string $column)
{
    if (!preg_match("/^[a-zA-Z_][a-zA-Z0-9_]*$/", $column))
        throw new \Exception("invalid column name: '{$column}'");
    return $column;
}