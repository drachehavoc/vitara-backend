<?php 

use \helper\Input as type;

return function() 
{
    $pdo = new \helper\PDO();
    $inp = new \helper\Input($this->request->search);

    $inp->check('nome', null, new type\tString());
    $inp->check('nono', null, new type\tString());

    return $inp->array;

    // $stm = $pdo->prepare('SELECT * FROM pessoa WHERE id=:id');
    // $stm->execute(['id'=>1]);
    // return $stm->fetchAll(\PDO::FETCH_ASSOC);

    // $telefone = $pdo
    //     ->search('SELECT * FROM telefone WHERE pessoa=?')
    //     ->addColumn('aew', function() {
    //         return "NADA NADA NADA";
    //     });

    // $pessoa = $pdo
    //     ->search('SELECT * FROM pessoa WHERE nome=?')
    //     ->whereValues("Daniel de Andrade Varela")
    //     ->addColumn("telefone", function ($pessoa) use ($telefone) {
    //         return (clone $telefone)->whereValues($pessoa->id)->fetch();
    //     });

    // return $pessoa->fetch();

    // return $pdo->select('SELECT * FROM pessoa WHERE nome=?', ["Daniel de Andrade Varela"], function ($pessoa) {
    //     $pessoa->telefone = $this->select('SELECT * FROM telefone WHERE pessoa=?', (Array)$pessoa->id);
    // });
};
