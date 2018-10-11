<?php

namespace Helper\Relational\Map;

class Insert 
{
    protected $map;
    protected $columns = [];
    protected $values;

    function __construct(\Helper\Relational\Map $map)
    {
        $this->values = new Values();
        $this->map = $map;
    }

    function __get($name)
    {
        throw new \Core\Exception\InaccessibleAttribute(); 
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

    function execute()
    {
        $data = $this->values->mountSimple();
        $query = $this->map->queryInsert(
            $this->map->raw->table,
            $this->columns,
            array_keys($data)
        );

        $stmt = $this->map->raw->pdo->prepare($query);
        $stmt->execute($data);
        
        die('retornar o id inserido e executar query filha');

        return $query;
    }
}