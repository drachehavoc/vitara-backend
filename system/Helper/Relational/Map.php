<?php

namespace Helper\Relational\Map;

const ANCHOR = "__LJHNMKHNKSHBNJDHBNJDHN____LJHNMKHNKSHBNJDHBNJDHN__";

function Conditional(string $col, string $cond, $val, ... $vals)
{
    return new Condition($col, $cond, $val, ... $vals);
}

function Cond(string $col, string $cond, $val, ... $vals)
{
    return Conditional($col, $cond, $val, ... $vals);
}

namespace Helper\Relational;

class Map
{

    public $raw;
    private $select;

    function __construct(string $table, PDO $pdo = null)
    {
        $this->raw = (Object)[
            "pdo"   => $pdo ?? new PDO,
            "table" => Map\Check::tableName($table)
        ];
    }

    function __get(string $name)
    {
        switch ($name)
        {
            case "select":
                return $this->select ?? $this->select = new Map\Select($this);
        }
    }
}