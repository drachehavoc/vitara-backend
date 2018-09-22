<?php return function () {
    // print_r($this);

    $this
        ->regex('{POST/nome/(?<nome>[a-zA-Z ]+)}', include 'teste.php')
    ;
};