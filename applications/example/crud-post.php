<?php 

use \Helper\Relational\Map;

return function () 
{  
    $pessoa = Map
        ::table("pessoas")
        ->insert
        ->value("nome", Map::ANCHOR, "ancora1")
        ->value("nascimento", "2019-01-01")
        // ->value("idade", Map::ANCHOR, "ancora2")
        // ->value("apelido", "dunha")
        ->anchors([
            "ancora1" => "Dunha da Silva " . random_int(0, 100),
            "ancora2" => random_int(14, 18)
        ]);

    return $pessoa->execute();
};