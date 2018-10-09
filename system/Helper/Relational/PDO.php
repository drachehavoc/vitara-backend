<?php

namespace Helper\Relational;

class PDO extends \PDO
{
    function __construct()
    {
        parent::__construct(
            "mysql:host=null:3306;dbname=null", 
            "root", 
            "", 
            [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
            ]
        );
    }
}