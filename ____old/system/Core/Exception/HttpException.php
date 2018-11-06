<?php

namespace Core\Exception;

class HttpException extends \Exception
{
    protected $httpStatus = 500;

    public function getHttpStatus()
    {
        return $this->httpStatus;
    }
}