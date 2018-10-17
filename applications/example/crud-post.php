<?php 

use \Helper\Relational\Map;

return function () 
{  
    $cond = Map
        ::cond('id', '>', Map::anchor('ancora3'));

    $pessoa = Map
        ::table('pessoas')
        ->save
        ->value('nome', Map::anchor('ancora1'))
        ->value('nascimento', '2019-01-01')
        // ->condition($cond)
        // ->callback()
        ->anchors([
            'ancora1' => 'Dunha da Silva ' . random_int(0, 100),
            'ancora2' => random_int(14, 18),
            'ancora3' => 15
        ]);

    $pessoa->execute();

    return $pessoa->getAffected();
};