<?php

namespace Helper\Relational\Map;

class Save 
{
    protected $map;
    protected $columns = [];
    protected $values;
    protected $anchors = [];
    protected $callbacks = [];
    protected $condition = null;

    function __construct(\Helper\Relational\Map $map)
    {    
        $this->values = new Values();
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
    
    function value(string $column, $value) : Self
    {
        $this->values->set($column, $value);
        return $this;
    }
    
    function condition(Condition $condition) : Self
    {
        $this->condition = $condition;
        return $this;
    }
    
    function anchors(Array $anchors) : Self
    {
        $this->anchors = $anchors;
        return $this;
    }

    function execute() : int
    {
        $this->values->anchors($this->anchors);

        if ($this->condition)
            $this->condition->anchors($this->anchors);

        $count = $this->map->raw->driver->save(
            $this->map->raw->table,
            $this->values,
            $this->condition
        );

        return $count;
    }

    function getAffected()
    {
        return $this->condition
            ? $this->map->raw->driver->select(
                $this->map->raw->table,
                [],
                $this->condition
            )
            : 'AIII CARAI TEM QUE FAZER O GET DO INSERT';
    }
}