<?php 

use \helper\InputCheck as type;

return function() {
    $inputCheck = new \helper\InputCheck();
    
    $inputCheck
        ->query('nome', false, new type\tString)
        ->query('NONO', false, new type\tString)
        ->body('nome', false, new type\tString)
        ;
        
    $select = new \helper\Select('NOME', $inputCheck);

    print_r($inputCheck->query);
    print_r($inputCheck->body);
    print_r($inputCheck->errors);
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