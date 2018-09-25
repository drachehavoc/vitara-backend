<?php return function () 
{   
    $this->regex('{^POST/nome/(?<nome>[a-zA-Z ]+)}', include 'teste.php');
};