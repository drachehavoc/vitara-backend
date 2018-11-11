<?php

namespace Helper;

class PDO extends \PDO
{
    function __construct(string $rootConfigKey = null, string ...$configKeys)
    {
        try {
            $cnf = \Core\Config
                ::getInstance()
                ->get($rootConfigKey, ...$configKeys);
        } catch (\Exception $e) {
            throw new \Exception("Não foi encontrada a chave `{$rootConfigKey}` de configuração para o Helper\PDO, verifique se a chave foi carregada me Core\Config");
        }

        $usr = $cnf['user'] ?? 'root';
        $pwd = $cnf['password'] ?? '';
        $drv = $cnf['driver'] ?? 'mysql';
        $hst = $cnf['host'] ?? '127.0.0.1';
        $ort = $cnf['port'] ?? '3306';
        $dbn = $cnf['database'] ?? 'test';
        $dsn = $cnf['dsn'] ?? '{driver}:host={host}:{port};dbname={database}';

        $dsn = str_replace(
            ['{driver}', '{host}', '{port}', '{database}'],
            [$drv, $hst, $ort, $dbn],
            $dsn
        );

        parent::__construct(
            $dsn,
            $usr,
            $pwd,
            [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            ]
        );
    }
}