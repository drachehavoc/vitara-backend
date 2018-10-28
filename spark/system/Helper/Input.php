<?php 

namespace Helper;

class Input
{
    protected static $instance = null;

    protected $receivedData = [];
    protected $contentType;

    static function getInstance()
    {
        return self::$instance ?? self::$instance = new self();
    }

    function __construct()
    {
        $this->contentType = $contentType = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'];
        
        // application/x-www-form-urlencoded
        // multipart/form-data; boundary=--------------------------617531026432792114026634
        // text/plain
        // application/javascript
        // application/xml
        // text/xml
        // text/html
        
        // if ($contentType === 'application/json')
        //     return $this->receiveJson();

        throw new \Exception("`$contentType` ainda não é suportado pela classe Helper\\Input");
    }

    function receiveJson()
    {

    }

    // function receiveUploadFile()
    // {
    //     // file_put_contents(__DIR__ . DS . 'img.jpg', file_get_contents('php://input'));

    //     echo extension_loaded('fileinfo') ? 'sim' : 'não';
    //     print_r(get_loaded_extensions());

    //     $file = file_get_contents('php://input');
    //     $info = new finfo(FILEINFO_MIME_TYPE);
    //     $type = $info->buffer($file);
    //     echo $type;
    // }
}