<?php

namespace Helper\Relational\Map;

class Select 
{
    protected $map;
    protected $condition;
    protected $columns = [];
    protected $customColumnsFunctions = [];
    protected $page = 1;
    protected $offset = 100;

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
            case 'conditional':
                return $this->condition;
            
            default:
                throw new \Core\Exception\InaccessibleAttribute(); 
        }
    }

    private function customColumnSelect(string $column, $select)
    {
        $this->customColumnsFunctions[$column] = function ($row) use ($select) 
        {
            $select->condition->anchors($row);
            return $select->fetch();
        };
        return $this;
    }

    private function customColumnFunction(string $column, $function)
    {
        $this->customColumnsFunctions[$column] = $function;
        return $this;
    }

    function columns($column, ... $columns)
    {
        array_unshift($columns, $column);
        $this->columns = array_merge($this->columns, $columns);
        return $this;   
    }

    function condition(Condition $condition)
    {
        $this->condition = $condition;
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
        throw new \Core\Exception\FobiddenType('aim', $aim, 'callable', 'Map\Select');
    }

    function fetch()
    {
        $raw = $this->map->raw;   
        $result = $raw->driver->select(
            $raw->table,
            $this->columns,
            $this->condition,
            ($this->page-1) * $this->offset,
            $this->offset
        );

        if (!empty($this->customColumnsFunctions))
            foreach ($result as $k => &$row)
                foreach($this->customColumnsFunctions as $columnName => $function)
                    $row->{$columnName} = $function($row);
        return $result;
    }
}