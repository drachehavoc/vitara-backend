<?php 

use \Helper\Relational\Map;

return function () {
    $cond = Map::cond('id', '>', 0);

    $telefone = Map
        ::table('telefones')
        ->delete
        ->condition($cond)
        ->execute();

    $cond = Map::cond('id', '=', Map::anchor("code"));

    $pessoa = Map
        ::table('pessoas')
        ->delete
        ->condition($cond)
        ->anchors([
            'code' => 1
        ]);

    return $pessoa->execute(Map\Save::GET_ROWS, 'id', 'nome');
    // return $pessoa->execute(Map\Save::GET_BOOLEAN);
    // return $pessoa->execute(Map\Save::GET_COUNT);
};