<?php return function () {
    $this->regex('{^PUT/evento}', function () {
        return $this->request->body->nome;
    });
};