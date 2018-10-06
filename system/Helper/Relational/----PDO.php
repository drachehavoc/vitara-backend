<?php

namespace Helper\Relational;

class PDO extends \PDO
{
    function __construct()
    {
        parent::__construct(
            "mysql:host=127.0.0.1:3306;dbname=pessoas", 
            "root", 
            "", 
            [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
            ]
        );
    }
}