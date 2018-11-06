<?php

namespace Core\Exception;

class GateNotFound extends HttpException
{
    protected $httpStatus = 404;
    // 'Gate não definido para o host `' . HOST . '`'
}