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
}