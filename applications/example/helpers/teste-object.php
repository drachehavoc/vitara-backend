<?php return new class () {
    function __construct() 
    {
        // echo "object";
    }
    
    function do()
    {
        global $ambue;
        echo "custom object";
        print_r($ambue);
    }
};