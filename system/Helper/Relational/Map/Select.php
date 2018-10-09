<?php

namespace Helper\Relational\Map;

class Select 
{
    protected $map;
    protected $condition;
    protected $columns = [];
    protected $customColumnsFunctions = [];

    function __construct(\Helper\Relational\Map $map)
    {
        $this->map = $map;
    }

    function __get($name)
    {
        switch ($name)
        {
            case 'cond':
            case 'condition':
                return $this->condition;
        }
        throw new \Exception("propriedade '{$name}' inacessivel na classe '". __CLASS__."'"); 
    }

    function columns($column, ... $columns)
    {
        array_unshift($columns, $column);
        foreach($columns as $k => &$col) 
        {
            if (is_array($col)) 
            {
                if (count($col) == 1) 
                {
                    $column = Check::columnName(array_keys($col)[0]);
                    $alias = Check::columnAlias(array_values($col)[0]);
                    $columns[$k] = "{$column} as '{$alias}'";
                    continue;
                }
                throw new \Exception("para criar apelidos para colunas utilize ['column'=>'alias'] <-- melhorar");
            }
            $col = Check::columnName($col);
        }
        $this->columns = array_merge_recursive($this->columns, $columns);
        return $this;
    }

    function condition(Condition $condition)
    {
        $this->condition = $condition;
        return $this;
    }

    private function customColumnSelect(string $column, $select)
    {
        $this->customColumnsFunctions[$column] = function ($row) use ($select) 
        {
            $select->getCondition()->setAnchors((Array)$row);
            return $select->fetch();
        };
        return $this;
    }

    private function customColumnFunction(string $column, $function)
    {
        $this->customColumnsFunctions[$column] = $function;
        return $this;
    }

    function customColumn(string $column, $aim)
    {
        switch (true)
        {
            case is_callable($aim):
                return $this->customColumnFunction($column, $aim);
                
            case $aim instanceof Self:
                return $this->customColumnSelect($column, $aim);
        }
    }

    private function prepareQuery()
    {
        $columnsName = count($this->columns) 
            ? implode(','.PHP_EOL.'    ', $this->columns) 
            : PHP_EOL.'    *';
        
        $conditionMeta = $this->condition->getPDOParams(); 

        $query = "SELECT" . PHP_EOL
               . "    {$columnsName}" . PHP_EOL
               . "FROM " . PHP_EOL
               . "    {$this->map->raw->table}" . PHP_EOL
               . "WHERE " . PHP_EOL
               . "    {$conditionMeta->query}";

        return [
            "query"  => $query,
            "values" => $conditionMeta->values
        ];
    }

    function getCondition()
    {
        return $this->condition;
    }

    function getCond()
    {
        return $this->condition;
    }

    function debug()
    {
        $prep = $this->prepareQuery();
        $keys = array_keys($prep['values']);
        $values = array_values($prep['values']);
        return (Object)array_merge($prep, ["fullQuery" => str_replace($keys, $values, $prep['query'])]);
    }

    function fetch()
    {
        $prep = $this->prepareQuery();
        $stmt = $this->map->raw->pdo->prepare($prep['query']);
        $stmt->execute($prep['values']);
        $result = $stmt->fetchAll(\PDO::FETCH_OBJ);
        if (!empty($this->customColumnsFunctions))
            foreach ($result as $k => &$row)
                foreach($this->customColumnsFunctions as $columnName => $function)
                    $row->{$columnName} = $function($row);
        return $result;
    }
}