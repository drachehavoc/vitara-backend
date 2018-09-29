<?php

namespace APPLICATION;

const NAME       = "Aplicação de Exemplo";
const PRODUCTION = false;
const LANGUAGE   = 'pt-br';
const TIMEZONE   = 'America/Sao_Paulo';
const HELPERS    = HOME.'helpers'.DS;
const LOGS       = HOME.'logs'.DS;
const ROUTES     = [
    '{^POST/nome/(?<nome>[a-zA-Z ]+)}' => 'teste',
];