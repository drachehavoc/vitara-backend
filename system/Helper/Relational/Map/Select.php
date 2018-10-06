<?php

namespace Helper\Relational\Map;

class Select 
{
    protected $map;
    protected $condition;
    protected $columns = [];

    function __construct(\Helper\Relational\Map $map)
    {
        $this->map = $map;
    }

    function columns(string $column, string ... $columns)
    {
        array_unshift($columns, $column);
        foreach($columns as &$col)
            $col = checkColumnName($col);
        $this->columns = array_merge_recursive($this->columns, $columns);
        return $this;
    }

    function condition(Condition $condition)
    {
        $this->condition = $condition;
        return $this;
    }


    private function prepareQuery()
    {
        $columnsName = implode(','.PHP_EOL.'    ', $this->columns);
        $columnsValues = [];
        $query = "SELECT" . PHP_EOL
               . "    {$columnsName}" . PHP_EOL
               . "FROM " . PHP_EOL
               . "    {$this->map->raw->table}" . PHP_EOL
               . "WHERE " . PHP_EOL
               . "    {$this->condition}";
        
        return [
            "query"  => $query,
            "values" => $this->condition->getValues()
        ];
    }

    function debug()
    {
        $prep = $this->prepareQuery();
        $keys = preg_filter('/^/', ':', array_keys($prep['values']));
        $values = preg_filter('/^(.+?)$/', '`$1`', array_values($prep['values']));
        return (Object)array_merge($prep, ["fullQuery" => str_replace($keys, $values, $prep['query'])]);
    }

    function fetch($type = \PDO::FETCH_ASSOC)
    {
        $prep = $this->prepareQuery();
        $stmt = $this->map->raw->pdo->prepare($prep['query']);
        $stmt->execute($prep['values']);
        return $stmt->fetchAll($type);
    }
}