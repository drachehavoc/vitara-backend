<?php 

use \Helper\Relational\Map;

return function () 
{
    $tPessoa = new Map("pessoa");
    $pessoas = $tPessoa
        ->select
        ->columns("nome")
        // ->condition(
        //     Map\Condition('col_aaa', '=', 'valor')
        //             ->and('col_bbb', '<', 'valor')
        //             ->or ('col_ccc', '>', 'valor')
        //             ->and('col_ddd', 'between', 0, 1)
        //             ->and('col_eee', 'in', 0, 1, 2, 3, 4))
        ->condition(Map\Condition('id', '=', 1));


    // print_r($pessoas->debug()->fullQuery);
    // echo PHP_EOL;
    // print_r($pessoas->debug()->query);
    // echo PHP_EOL;
    // print_r($pessoas->debug()->values);
    // die();
    
    // $pdo = $tPessoa->raw->pdo;
    // $teste = $pdo->prepare("SELECT :col1 FROM pessoa WHERE id=:val2");
    // $teste->execute([
    //     "col1" => 'id',
    //     "val2" => 1
    // ]);
    // return $teste->fetchAll(\PDO::FETCH_ASSOC);

    return $pessoas->fetch();
};