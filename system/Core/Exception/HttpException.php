<?php

namespace Core\Exception;

class HttpException extends \Exception {
    protected $httpCode = 500;

    public function getHttpCode()
    {
        return $this->httpCode;
    }
}