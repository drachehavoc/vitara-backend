<?php

namespace helper;

class Select {
    public $pdo;
    private $query;
    private $where = [];
    private $additionalColumns = [];

    function __construct (\helper\PDO $pdo, $query) 
    {
        $this->query = $query;
        $this->pdo = $pdo;
    }

    function whereValues (... $param)
    {
        $this->where = array_merge($this->where, $param);
        return $this;
    }
    
    function addColumn ($columnName, $function)
    {
        $this->additionalColumns[$columnName] = \Closure::bind($function, $this); 
        return $this;
    }

    function fetch ()
    {
        $s = $this->pdo->prepare($this->query);
        $s->execute($this->where);
        $res = $s->fetchAll(\PDO::FETCH_OBJ);
        
        if (empty($this->additionalColumns))
            return $res;

        foreach ($res as $row)
            foreach ($this->additionalColumns as $columnName => $columnFunction) 
                $row->{$columnName} = $columnFunction($row, $res);

        return $res;
    }
}