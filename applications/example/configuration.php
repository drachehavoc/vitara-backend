<?php

namespace APPLICATION;

const NAME = "Aplicação de Exemplo";
const PRODUCTION = false;
const LANGUAGE = 'pt-br';
const TIMEZONE = 'America/Sao_Paulo';
const HELPERS = HOME . 'helpers' . DS;
const LOGS = HOME . 'logs' . DS;
const ROUTES = [
    '{^.*?/relational}' => 'relational',

    // ERROR TEST
    '{^.*?/error}' => 'test-errors',
    '{^.*?/error/(?<err>[0-9]+)}' => 'test-errors',
    
    // CRUD TEST
    # get
    '{^GET/crud}' => 'crud-get',
    '{^GET/crud/(?<nome>[a-zA-Z ]+)}' => 'crud-get',

    # post
    '{^POST/crud}' => 'crud-post',

    # delete
    '{^DELETE/crud}' => 'crud-delete',
];