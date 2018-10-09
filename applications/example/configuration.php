<?php

namespace APPLICATION;

const NAME       = "Aplicação de Exemplo";
const PRODUCTION = false;
const LANGUAGE   = 'pt-br';
const TIMEZONE   = 'America/Sao_Paulo';
const HELPERS    = HOME.'helpers'.DS;
const LOGS       = HOME.'logs'.DS;
const ROUTES     = [
    // ERROR TEST
    '{^.*?/error}'                => 'test-errors',
    '{^.*?/error/(?<err>[0-9]+)}' => 'test-errors',
    
    // CRUD TEST
    '{^GET/crud/(?<nome>[a-zA-Z ]+)}' => 'crud-get',
];