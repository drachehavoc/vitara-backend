<?php

namespace Helper\Relational;

class PDO extends \PDO
{
    function __construct(Array $config)
    {
        $driver   = $config['driver']    ?? Config::driver; 
        $host     = $config['host']      ?? Config::host;
        $database = $config['database']  ?? Config::database;
        $user     = $config['user']      ?? Config::user;
        $password = $config['password']  ?? Config::password;
        parent::__construct(
            "{$driver}:host={$host};dbname={$database}",
            $user,
            $password,
            [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                \PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                \PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
    }
}