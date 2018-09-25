<?php

namespace helper;

class PDO extends \PDO
{
    function __construct()
    {
        Parent::__construct(
            'mysql:host=localhost;dbname=pessoas', 
            'root', 
            '',  
            [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }

    function select($query, $data, $func=null)
    {
        $s = $this->prepare($query);
        $s->execute($data);
        $res = $s->fetchAll(\PDO::FETCH_OBJ);
        
        if ($func)
            foreach ($res as $key => $row) 
                \Closure::bind($func, $this)($row, $res);

        return $res;
    }

    function search($sql)
    {
        return new \helper\Select($this, $sql);
    }
}