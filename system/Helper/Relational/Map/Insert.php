<?php

namespace Helper\Relational\Map;

class Insert 
{
    const insertedId = '[id]';

    protected $map;
    protected $columns = [];
    protected $values;
    protected $callbacks = [];

    function __construct(\Helper\Relational\Map $map)
    {
        $this->values = new Values();
        $this->map = $map;
    }

    function __get($name)
    {
        switch ($name)
        {
            case 'map':
                return $this->map;
            
            default:
                throw new \Core\Exception\InaccessibleAttribute(); 
        }
    }

    function anchors(Array $achors)
    {
        $this->values->anchors($achors);
        return $this;
    }

    function value(string $col, $value, ... $values)
    {
        $this->columns[] = Check::columnName($col);
        $this->values->add($value, ... $values);
        return $this;
    }
    
    function callback($aim, ... $values)
    {
        switch (true)
        {
            case is_callable($aim):
                return $this->callbackFunction($aim);
                
            case $aim instanceof Self:
                return $this->callbackInsert($aim, ... $values);
        }
        throw new \Core\Exception\FobiddenType('aim', $aim, 'callable', 'Map\Insert');
    }

    private function callbackFunction($aim)
    {
        $this->callbacks[] = $aim;
        return $this;
    }

    private function callbackInsert($aim, string $alias = null, Array $value, ... $values)
    {
        $values = array_merge([$value], $values);
        $this->callbacks[] = function(&$result) use ($aim, $alias, $values)
        {
            $alias = $alias ?? $aim->map->raw->table;
            foreach($values as $value) {
                $id = $aim->anchors(array_merge((Array)$result, (Array)$value))->execute()[ Self::insertedId ];
                $result[ $alias ][] = $id;
            }
        };
        return $this;
    }

    function execute()
    {
        $data = $this->values->mountSimple();
        $query = $this->map->queryInsert(
            $this->map->raw->table,
            $this->columns,
            array_keys($data)
        );

        $pdo = $this->map->raw->pdo;
        $stmt = $pdo->prepare($query);
        $stmt->execute($data);
        $return[ Self::insertedId ] = $pdo->lastInsertId();

        foreach($this->callbacks as $function)
            $function($return);

        return $return;
    }
}