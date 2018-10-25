<?php

namespace Helper\Relational;

class Map
{
    protected $raw;
    
    const ANCHOR = "__LJHNMKHNKSHBNJDHBNJDHN__";

    function __construct(string $table, \PDO $pdo = null)
    {   
        $pdo = $pdo ?? new PDO;
        $driverName = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $driverClass = __NAMESPACE__."\\Driver\\{$driverName}";
        $driver = new $driverClass($pdo);
        
        $driver->tableExists($table);

        $this->raw = (Object)[
            'pdo'=> $pdo,
            'driver' => $driver,
            'table' => $table
        ];
    }

    function __get(string $name)
    {
        switch ($name)
        {
            case "select":
                return new Map\Select($this);
            
            case "save":
                return new Map\Save($this);

            case "delete":
                return new Map\Delete($this);
            
            case "raw":
                return $this->raw;

            default:
                throw new \Core\Exception\InaccessibleAttribute(); 
        }
    }

    static function anchor(string $alias)
    {
        return [Self::ANCHOR, $alias];
    }
    
    static function table(string $table, PDO $pdo = null)
    {
        return new Self($table, $pdo);
    }
    
    static function condition(string $col, string $cond, $val, ... $vals)
    {
        return new Map\Condition($col, $cond, $val, ... $vals);
    }
    
    static function cond(string $col, string $cond, $val, ... $vals)
    {
        return Self::condition($col, $cond, $val, ... $vals);
    }
    
    static function where(string $col, string $cond, $val, ... $vals)
    {
        return Self::condition($col, $cond, $val, ... $vals);
    }
}