<?php

namespace Core\Exception;

class EndPointNotFound extends HttpException
{
    protected $httpStatus = 404;
}