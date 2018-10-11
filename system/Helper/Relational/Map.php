<?php

namespace Helper\Relational;

class Map
{
    protected $raw;
    protected $select;
    protected $insert;
    
    const ANCHOR = "__LJHNMKHNKSHBNJDHBNJDHN__";
    const querys = [
        "mysql" => [
            "select" => "SELECT {columns} FROM {table} WHERE {where} LIMIT {limit},{offset}",
            "insert" => "INSERT INTO {table}({columns}) VALUES({values})"
        ]
    ];

    static function table(string $table, PDO $pdo = null)
    {
        return new Self($table, $pdo);
    }
    
    static function conditional(string $col, string $cond, $val, ... $vals)
    {
        return new Map\Condition($col, $cond, $val, ... $vals);
    }
    
    static function cond(string $col, string $cond, $val, ... $vals)
    {
        return Self::conditional($col, $cond, $val, ... $vals);
    }
    
    static function where(string $col, string $cond, $val, ... $vals)
    {
        return Self::conditional($col, $cond, $val, ... $vals);
    }

    function __construct(string $table, PDO $pdo = null)
    {
        $pdo = $pdo ?? new PDO;
        $this->raw = (Object)[
            "pdo"    => $pdo,
            "table"  => Map\Check::tableName($table),
            "driver" => $pdo->getAttribute(PDO::ATTR_DRIVER_NAME)
        ];
    }

    function __get(string $name)
    {
        switch ($name)
        {
            case "select":
                return $this->select ?? $this->select = new Map\Select($this);
            
            case "insert":
                return $this->insert ?? $this->insert = new Map\Insert($this);
                
            case "raw":
                return $this->raw;

            default:
                throw new \Core\Exception\InaccessibleAttribute(); 
        }
    }

    function querySelect(Array $columns, string $table, string $where, int $limit, int $offset)
    {
        if ( !isset(Self::querys[ $this->raw->driver ]['select']) )            
            throw new \Exception("driver `{$this->raw->driver}` ainda não suportado <-- melhorar");
        
        $replace = [
            "{columns}" => count($columns) ? implode(', ', $columns) : "*",
            "{table}"   => $table,
            "{where}"   => $where,
            "{limit}"   => $limit,
            "{offset}"  => $offset
        ];

        return str_replace(array_keys($replace), array_values($replace), Self::querys[ $this->raw->driver ]['select']);
    }
    
    function queryInsert(string $table, Array $columns, Array $anchors)
    {
        if ( !isset(Self::querys[ $this->raw->driver ]['select']) )            
        throw new \Exception("driver `{$this->raw->driver}` ainda não suportado <-- melhorar");

        $replace = [
            "{columns}" => implode(', ', $columns),
            "{table}"   => $table,
            "{values}"  => ":". implode(', :', $anchors)
        ];
        
        return str_replace(array_keys($replace), array_values($replace), Self::querys[ $this->raw->driver ]['insert']);
    }
}