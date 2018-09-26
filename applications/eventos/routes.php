<?php return function () {
    $this->regex('{^[PUT,POST]/evento}', function () {
        return $this->request->body->nome;
    });
};