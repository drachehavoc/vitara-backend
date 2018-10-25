<?php

namespace Helper\Relational\Map;

class Delete 
{
    const GET_ID       = '__BVFGYHJNBVCXOR__';
    const GET_IDS      = Self::GET_ID;
    const GET_ROW      = '__KJHGHJJHGHJKJH__';
    const GET_ROWS     = Self::GET_ROW;
    const GET_COUNT    = '__JHGFGHJHGFDFGH__';
    const GET_BOOLEAN  = '__IUYTYUYTRTYUYT__';

    protected $map;
    protected $anchors = [];
    protected $callbacks = [];
    protected $condition = null;
    protected $returnType = [Self::GET_COUNT, []];

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


    function setReturnType($type, ... $params)
    {
        $this->returnType = [$type, $params];
        return $this;
    }

    function execute($returnType = null, ... $params)
    {
        $raw = $this->map->raw;
        $this->condition->anchors($this->anchors);

        $count = $raw->driver->delete(
            $raw->table,
            $this->condition
        );

        $results = $this->returnFormat($raw, $count);

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
        return $raw->driver->select($raw->table, $this->returnType[1], $cond);
    }

    private function getAffectedId($raw)
    {
        return [$column => (int)$lastId];
    }
}