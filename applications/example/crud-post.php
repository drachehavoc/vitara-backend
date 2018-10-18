<?php 

use \Helper\Relational\Map;

return function () 
{  
    $telefone = Map
        ::table('telefones')
        ->save
        ->setReturnType(Map\Save::GET_ROWS, 'id', 'ddd', 'numero')
        ->value('pessoa', Map::anchor('id'))
        ->value('ddd', Map::anchor('anc_ddd'))
        ->value('numero', Map::anchor('anc_numero'));

    $telefoneData = [
        [
            'anc_ddd' => '47',
            'anc_numero' => '992022970'
        ], [
            'anc_ddd' => '47',
            'anc_numero' => '89898989'
        ]
    ];

    $callback = function (&$row)
    {
        $row->teste[] = 'Adicionado - ' . random_int(0, 100);
    };

    $cond = Map
        ::cond('id', '<', Map::anchor('ancora3'));

    $pessoa = Map
        ::table('pessoas')
        ->save
        ->value('nome', Map::anchor('ancora1'))
        ->value('nascimento', '2019-01-01')
        ->condition($cond)
        ->callback($telefone, 'column-alias', ... $telefoneData)
        // ->callback($callback)
        // ->callback($callback)
        ->anchors([
            'ancora1' => 'Dunha da Silva ' . random_int(0, 100),
            'ancora2' => random_int(14, 18),
            'ancora3' => 15
        ]);

    return $pessoa->execute(Map\Save::GET_ROWS, 'id', 'nome');
    // return $pessoa->execute(Map\Save::GET_BOOLEAN);
    // return $pessoa->execute(Map\Save::GET_COUNT);
};