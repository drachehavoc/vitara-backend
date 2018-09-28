<?php

header('Content-Type: application/json');

require "constants.php";
require "autoload.php";

try {
    new Core\System();
    die;
} 

catch (Core\Exception\HttpException $e) {
    $exp = $e;
    $httpCode = $e->getHttpCode();
} 

catch (\Exception $e) {
    $exp = $e;
    $httpCode = 500;
} 

finally {
    http_response_code($httpCode);
    echo json_encode([
        "error" => [
            'message' => $exp->getMessage(),
            'detail'  => $exp->getCode(),
            'code'    => $httpCode,
            'type'    => get_class($exp),
            'trace'   => \APPLICATION\PRODUCTION ? NULL : $exp->getTrace() 
        ]
    ]);
}