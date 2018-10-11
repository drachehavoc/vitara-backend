<?php 

use \Helper\Relational\Map;

return function () 
{  
    // $x = new Map\Values();

    // $x->add(Map::ANCHOR, 'AAA');
    // $x->add('BBB');
    // $x->add('CCC');
    // $x->add('DDD');
    // $x->add('EEE');
    // $x->add('FFF');
    // $x->add('GGG');

    // $x->anchors([
    //     "AAA" => "ANCORA ALTERADA"
    // ]);

    // $x->test();

    // die;

















    // die("--------------------");
    
    $x = new Map\ColumnsControl();
    $x->add(1000);
    $x->add(1000);

    print_r($x->getValues());
    
    die('--------');

    $pessoa = Map
        ::table("pessoas")
        ->insert
        ->value("nome", Map::ANCHOR, "ancora1")
        ->value("idade", 100)
        ->value("apelido", "dunha");

    $pessoa->anchors([
        "ancora1" => "Dunha da Silva " . random_int(0, 100)
    ]);

    return $pessoa->execute();
};