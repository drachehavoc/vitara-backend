<?php 

use \Helper\Relational\Map;

return function () 
{  

    $telefones = Map
        ::table('telefones')
        ->insert
        ->value('ddd', Map::ANCHOR, 'anch_ddd')
        ->value('numero', Map::ANCHOR, 'anch_numero')
        ->value('pessoa', Map::ANCHOR, Map\Insert::insertedId);

    $dadosTelefones = [
        ['anch_ddd' => '01', 'anch_numero' => '77778888'],
        ['anch_ddd' => '02', 'anch_numero' => '77778888'],
        ['anch_ddd' => '03', 'anch_numero' => '77778888'],
        ['anch_ddd' => '04', 'anch_numero' => '77778888'],
        ['anch_ddd' => '05', 'anch_numero' => '77778888'],
        ['anch_ddd' => '06', 'anch_numero' => '77778888']
    ];

    $functionInsertTelefones = function(&$result) use ($telefones, $dadosTelefones)
    {
        foreach($dadosTelefones as $dados)
        {
            $telefones->anchors(array_merge([
                Map\Insert::insertedId => $result[ Map\Insert::insertedId ],
            ], $dados))->execute();
        }
        $result = 'isso aqui é o parana uÊ';
    };

    $pessoa = Map
        ::table('pessoas')
        ->insert
        ->value('nome', Map::ANCHOR, 'ancora1')
        ->value('nascimento', '2019-01-01')
        // ->callback($telefones, ... $dadosTelefones)
        ->callback($functionInsertTelefones)
        ->anchors([
            'ancora1' => 'Dunha da Silva ' . random_int(0, 100),
            'ancora2' => random_int(14, 18)
        ]);

    return $pessoa->execute();
};