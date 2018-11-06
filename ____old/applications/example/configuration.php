<?php 
return [
    'routes' => [
    // // ERROR TEST
    // '{^.*?/error}' => 'test-errors',
    // '{^.*?/error/(?<err>[0-9]+)}' => 'test-errors',
    
    // // CRUD TEST
    // # get
    // '{^GET/crud}' => 'crud-get',
    // '{^GET/crud/(?<nome>[a-zA-Z ]+)}' => 'crud-get',
    
    // # post
    // '{^POST/crud}' => 'crud-post',
    
    // # delete
    // '{^DELETE/crud}' => 'crud-delete',

        '{.*}' => function () {
            return $this;
            // return "tudo acaba em pizza";
        },
    ]
];