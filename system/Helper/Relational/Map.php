<?php

namespace Helper\Relational;

require_once "functions.php";

class Map
{
    public $raw;
    private $select;

    function __construct(string $table, PDO $pdo = null)
    {
        $this->raw = (Object)[
            "pdo"   => $pdo ?? new PDO,
            "table" => Map\checkTableName($table)
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