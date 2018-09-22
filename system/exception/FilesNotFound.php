<?php 

namespace system\exception;

class FilesNotFound extends \Exception 
{
    function __construct($files)
    {
        $this->message = "Nenhum dos arquivos a seguir foram encontrado `". implode('`, `', $files) ."`.";
    }
}