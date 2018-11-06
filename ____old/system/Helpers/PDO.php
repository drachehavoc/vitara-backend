<?php

namespace Helper;

class PDO extends \PDO
{
    function __construct(Array $config)
    {
        $host     = $config['host']      ?? PDO\Config::host;
        $driver   = $config['driver']    ?? PDO\Config::driver; 
        $database = $config['database']  ?? PDO\Config::database;
        $user     = $config['user']      ?? PDO\Config::user;
        $password = $config['password']  ?? PDO\Config::password;
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