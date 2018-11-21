<?php 

return function () {
    new class('artigo') extends \Helper\RouteRest {
        function getMany() {
            return $this;
        }
    
        function getOne($id) {
            return $id;
        }        
    };
};