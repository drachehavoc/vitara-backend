<?php

namespace Helper\Relational\Map;

class Save 
{
    const GET_ID       = '__BVFGYHJNBVCXOR__';
    const GET_IDS      = Self::GET_ID;
    const GET_ROW      = '__KJHGHJJHGHJKJH__';
    const GET_ROWS     = Self::GET_ROW;
    const GET_COUNT    = '__JHGFGHJHGFDFGH__';
    const GET_BOOLEAN  = '__IUYTYUYTRTYUYT__';

    protected $map;
    protected $columns = [];
    protected $values;
    protected $anchors = [];
    protected $callbacks = [];
    protected $condition = null;
    protected $returnType = [Self::GET_COUNT, []];

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

    function callback($aim, ... $p) : self
    {
        switch (true)
        {
            case is_callable($aim):
                return $this->callbackCommon($aim);
                
            case is_object($aim) && $aim instanceof Self:
                return $this->callbackSave($aim, array_shift($p), $p);

            default:
                throw new \Exception("Tipo de `aim` não suportado por callback <--- melhorar");
        }
    }

    function setReturnType($type, ... $params)
    {
        $this->returnType = [$type, $params];
        return $this;
    }

    function execute($returnType = null, ... $params)
    {
        $this->values->anchors($this->anchors);
        $raw = $this->map->raw;

        if ($this->condition)
            $this->condition->anchors($this->anchors);

        if ($returnType)
            $this->setReturnType($returnType, ... $params);

        $count = $raw->driver->save(
            $raw->table,
            $this->values,
            $this->condition
        );

        $results = $this->returnFormat($raw, $count);

        foreach($this->callbacks as $callback)
            foreach((Array)$results as $result)
                $callback($result, $results);

        return $results;
    }
    
    private function returnFormat($raw, $count)
    {
        switch ($this->returnType[0])
        {
            case Self::GET_ROWS:
                return $this->getAffectedRows($raw);

            case Self::GET_IDS:
                return $this->getAffectedId($raw);

            case Self::GET_BOOLEAN:
                return (bool)$count;

            case Self::GET_COUNT:
                // default

            default:
                return $count;
        }
    }

    private function getAffectedRows($raw)
    {
        if ($this->condition) 
            return $raw->driver->select($raw->table, $this->returnType[1], $this->condition);   
        $last = $raw->pdo->lastInsertId();
        $pkey = $raw->driver->describeTable($raw->table)->primarys[0];
        $cond = new Condition($pkey, '=', $last);
        return $raw->driver->select($raw->table, $this->returnType[1], $cond);
    }

    private function getAffectedId($raw)
    {
        $lastId = $raw->pdo->lastInsertId();
        $column = $raw->driver->describeTable($raw->table)->primarys[0];
        if ($this->condition)
            return $raw->driver->select($raw->table, [$column], $this->condition);
        return [$column => (int)$lastId];
    }

    private function callbackCommon($aim)
    {
        $this->callbacks[] = $aim;
        return $this;
    }

    private function callbackSave($save, string $alias, $values)
    {
        $this->callbacks[] = function ($row, $rows) use ($save, $alias, $values) 
        {
            $imutableRow = (Array)$row;
            $row->{$alias} = [];
            foreach($values as $value)
                $row->{$alias}[] = $save->anchors($imutableRow + $value)->execute();
        };
        return $this;
    }
}