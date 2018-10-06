<?php

namespace APPLICATION;

const NAME       = "Aplicação de Exemplo";
const PRODUCTION = false;
const LANGUAGE   = 'pt-br';
const TIMEZONE   = 'America/Sao_Paulo';
const HELPERS    = HOME.'helpers'.DS;
const LOGS       = HOME.'logs'.DS;
const ROUTES     = [
    '{^.*?/error/(?<err>[0-9]+)}' => 'test-errors',
    '{^POST/crud/(?<nome>[a-zA-Z ]+)}' => 'crud-post',
];