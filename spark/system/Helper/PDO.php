<?php

namespace Helper;

class PDO extends \PDO
{
    function __construct(string $configKey = null)
    {
        try {
            $cnf = \Core\Config::getInstance()->get($configKey);
        } catch (\Exception $e) {
            throw new \Exception("Não foi encontrada a chave `{$configKey}` de configuração para o Helper\PDO, verifique se a chave foi carregada me Core\Config");
        }

        $user = $cnf['user'] ?? 'root';
        $password = $cnf['pass$password'] ?? '';
        $driver = $cnf['driver'] ?? 'mysql';
        $host = $cnf['host'] ?? '127.0.0.1';
        $port = $cnf['port'] ?? '3306';
        $database = $cnf['database'] ?? 'test';
        $dsn = $cnf['dsn'] ?? '{driver}:host={host}:{port};dbname={database}';

        $dsn = str_replace(
            ['{driver}', '{host}', '{port}', '{database}'],
            [$driver, $host, $port, $database],
            $dsn
        );

        parent::__construct(
            $dsn,
            $user,
            $password,
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            ]
        );
    }
}