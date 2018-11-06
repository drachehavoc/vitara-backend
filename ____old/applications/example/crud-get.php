<?php 

use \Helper\Relational\Map;

return function () {
    $telefones = Map
        ::table('telefones')
        ->select
        ->condition($condTel = Map::cond('pessoa', '=', Map::anchor('telefones_anc')));

    $functionSelectTelefones = function ($row) use ($telefones) {
        $telefones->condition->anchors($row);
        return $telefones->fetch();
    };

    $functionSelectTelefonesAlternative = function ($row) use ($telefones, $condTel) {
        $condTel->anchors($row);
        return $telefones->fetch();
    };

    $pessoas = Map
        ::table('pessoas')
        ->select
        ->columns('id', 'nome', 'nascimento', ['id' => 'telefones_anc'])
        ->customColumn('telefones_sel', $telefones)
        ->customColumn('telefones_fun', $functionSelectTelefones)
        ->customColumn('telefones_alt', $functionSelectTelefonesAlternative)
        ->customColumn('telefones_anc', $telefones) // replace column telefones_anc
        ->condition(Map::cond('id', '>=', 1));

    // die("resolver a situação das colunas que não podem receber apelidos, veja os itens comentados neste arquivo ".__FILE__."(".__LINE__.")");

    return $pessoas->fetch();
};