<?php

namespace Helper\Relational;

class PDO extends \PDO
{
    function __construct()
    {
        parent::__construct(
            Config::dsn, 
            Config::usr, 
            Config::psw, 
            [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
            ]
        );
    }
}