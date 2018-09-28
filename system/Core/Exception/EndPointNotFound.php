<?php

namespace Core\Exception;

class EndPointNotFound extends HttpException {
    protected $httpCode = 404;
}