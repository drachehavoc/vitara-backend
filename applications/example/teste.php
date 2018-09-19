<?php return function() {
    // $relational = $this->helper->load('relational');

    $this->input
        ->checkSearch('teste', false, 'string')
        ->checkSearch('nome' , false, 'string', 'params', 'to', 'type')
        ->checkBody('nome' , false, 'string', 'params', 'to', 'type')
        ->throw()
    ;

    print_r($this->input->search);
    print_r($this->input->body);

    // type
    // null
    // empty
    // mensage

    // $relational->checkQuery();

    // $this->helper->load('teste-function', 'custom-name');
    // $fn = $this->helper->load('teste-function');
    // $ob = $this->helper->load('teste-object');
    
    // echo "\r\n--------------------------\r\n";
    // $this->helper->{'custom-name'}();
    
    // echo "\r\n--------------------------\r\n";
    // $this->helper->{'teste-function'}();
    // echo "\r\n";
    // $this->helper->{'teste-object'}->do();
    
    // echo "\r\n--------------------------\r\n";
    // $fn();
    // echo "\r\n";
    // $ob->do();
};