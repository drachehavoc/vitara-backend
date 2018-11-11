<?php 

namespace Helper;

class Input
{
    protected $receivedData = [];
    protected $contentType;

    function __construct()
    {
        // application/x-www-form-urlencoded
        // multipart/form-data; boundary=--------------------------617531026432792114026634
        // text/plain
        // application/javascript
        // application/xml
        // text/xml
        // text/html

        $this->contentType = $contentType = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'];

        if ($contentType === 'application/json')
            return $this->receiveJson();

        throw new \Exception("Content type: `$contentType`, ainda não é suportado pela classe Helper\\Input");
    }

    function receiveJson()
    {
        $this->receivedData = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE)
            throw new \Exception("Não foi possivel interpretar o arquivo recebido como json: " . json_last_error_msg());
    }

    function filter($filters)
    {
        $result = [];
        $errors = [];
        foreach ($filters as $column => $type) {
            if (!($type instanceof \Helper\Input\Types\Type))
                throw new \Exception(__METHOD__ . " deve receber um array composto por chaves com os nomes dos campos recebidos pelo cliente e valores do tipo Helper\\Input\\Types\\Type");

            try {
                $value = $this->receivedData[$column] ?? null;
                $type->setName($column);
                $type->setValue($value);
                $type->checkNull();
                $type->mount();
                $result[$type->getName()] = $type->getValue();
            } catch (\Exception $e) {
                $errors[$type->getName()] = $e->getMessage();
            }
        }

        if (!empty($errors))
            throw new \Exception('Os seguintes erros foram encontrados durante a filtragem de dados: ' . implode(', ', $errors));

        return $result;
    }

    // function receiveUploadFile()
    // {
    //     // var fileReader = new FileReader();
    //     // fileReader.readAsArrayBuffer( $('input').files[0] );
    //     // fileReader.onload = function () {
    //     // 	console.log(fileReader.result);
    //     // };
    //     // file_put_contents(__DIR__ . DS . 'img.jpg', file_get_contents('php://input'));

    //     echo extension_loaded('fileinfo') ? 'sim' : 'não';
    //     print_r(get_loaded_extensions());

    //     $file = file_get_contents('php://input');
    //     $info = new finfo(FILEINFO_MIME_TYPE);
    //     $type = $info->buffer($file);
    //     echo $type;
    // }
}