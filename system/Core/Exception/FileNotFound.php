<?php

namespace Core\Exception;

class FileNotFound extends HttpException {
    protected $httpCode = 404;
}