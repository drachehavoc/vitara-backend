<?php return function() {
    $this->helper->load('teste-function', 'custom-name');
    $fn = $this->helper->load('teste-function');
    $ob = $this->helper->load('teste-object');
    
    echo "\r\n--------------------------\r\n";
    $this->helper->{'custom-name'}();
    
    echo "\r\n--------------------------\r\n";
    $this->helper->{'teste-function'}();
    echo "\r\n";
    $this->helper->{'teste-object'}->do();
    
    echo "\r\n--------------------------\r\n";
    $fn();
    echo "\r\n";
    $ob->do();
};