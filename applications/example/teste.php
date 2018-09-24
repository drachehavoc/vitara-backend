<?php 

use \helper\InputCheck as type;

return function() {
    $inputCheck = new \helper\InputCheck();
    $pdo        = new \helper\PDO();
    $pessoa     = new \helper\Select($pdo);
    $telefone   = new \helper\Select($pdo);

    $inputCheck
        ->query('nome', new type\tString)
        // ->query('NONO', new type\tString)
        ->body('nome', new type\tString)
        ->body('NONO', new type\tString, 'parameters', 'to', 'type')
        ;

    $telefone('telefone');

    return 
        $pessoa('pessoa')
        ->setColumns('nome', 'id')
        ->setPage(1)
        ->setLimit(100)
        ->setQuery($inputCheck->query)
        ->forEach('func', function($data){ return [
                "teste" => 123123,
                "same data" => $data['nome'].' - '.$data['id']
            ]; 
        })
        ->select('telefone', $telefone)
        ->fetch();
        
    // print_r($inputCheck->query);
    // print_r($inputCheck->body);
    // print_r($inputCheck->errors);
};
















        
//     $relational = $this->helper->load('relational');

//     $this->input
//         ->checkSearch('teste', false, 'string')
//         ->checkSearch('nome' , false, 'string', 'params', 'to', 'type')
        
//         ->checkBody('nome', false, 'string', 'params', 'to', 'type')
        
//         // ->throw()
//     ;

//     $relational->select('table-name', 2, 100, 'nome', 'telefone');

//     $relational
//         ->select 
//             ->page(2)
//             ->limit(100)
//             ->table('table-name')
//             ->columns('nome', 'telefone')
//             ->fetch();

//     print_r($this->input->search);
//     print_r($this->input->body);

//     $this->helper->load('teste-function', 'custom-name');
//     $fn = $this->helper->load('teste-function');
//     $ob = $this->helper->load('teste-object');
    
//     echo "\r\n--------------------------\r\n";
//     $this->helper->{'custom-name'}();
    
//     echo "\r\n--------------------------\r\n";
//     $this->helper->{'teste-function'}();
//     echo "\r\n";
//     $this->helper->{'teste-object'}->do();
    
//     echo "\r\n--------------------------\r\n";
//     $fn();
//     echo "\r\n";
//     $ob->do();
// };