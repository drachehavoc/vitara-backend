<?php

namespace Helper\Relational\Map;

class Insert 
{
    protected $map;
    protected $values;

    function __construct(\Helper\Relational\Map $map)
    {
        $this->values = new ColumnsControl();
        $this->map = $map;
    }

    function __get($name)
    {
        throw new \Core\Exception\InaccessibleAttribute(); 
    }

    function anchors(Array $achors)
    {
        $this->values->setAnchors($achors);
        return $this;
    }

    function value(string $col, $value, string $anchorName = null)
    {
        $this->values->add(Check::columnName($col), array_merge((Array)$anchorName), (Array)$value);
        return $this;
    }

    function execute()
    {
        print_r($this->values->getValues());
        $query = $this->map->queryInsert(
            $this->map->raw->table,
            ['a', 'b', 'c', 'd'],
            ['A', 'B', 'C', 'D']
        );
        return $query;
    }
}