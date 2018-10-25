<?php 

use Helper\Vanuatu\Relational\Collection as Collection;

return function () {
    $telefones = new Collection('telefones');
    $pessoas = new Collection('pessoas');
    $pessoas->condition('id', '>', 0);
    $pessoas->join($telefones);
    return $pessoas->find();
};