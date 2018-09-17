<?php return function () {
    // print_r($system);
    // print_r($this);

    $this
        ->regex('{/nome/(?<nome>\\w+)/idade/(?<idade>\\w+)}', include 'teste.php', true)
        ->regex('{/nome/(?<nome>\\w+)}', include 'teste.php', true)
        ->regex('{/\\w+/(?<nome>\\w+)}', include 'teste.php');
};