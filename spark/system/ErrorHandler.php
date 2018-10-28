<?php

class ErrorHandler
{
    const ERRORS = [
        1 => "E_ERROR",
        2 => "E_WARNING",
        4 => "E_PARSE",
        8 => "E_NOTICE",
        16 => "E_CORE_ERROR",
        32 => "E_CORE_WARNING",
        64 => "E_COMPILE_ERROR",
        128 => "E_COMPILE_WARNING",
        256 => "E_USER_ERROR",
        512 => "E_USER_WARNING",
        1024 => "E_USER_NOTICE",
        2048 => "E_STRICT",
        4096 => "E_RECOVERABLE_ERROR",
        8192 => "E_DEPRECATED",
        16384 => "E_USER_DEPRECATED",
        32767 => "E_ALL"
    ];

    protected static $instance = null;
    protected $phpErrors;
    protected $messages = [];

    static function getInstance()
    {
        return self::$instance ?? self::$instance = new self();
    }

    protected function __construct()
    {
        set_error_handler($this->bind('recoverable_error'));
        set_exception_handler($this->bind('exception'));
    }

    protected function bind(string $methodName)
    {
        return \Closure::bind(function (...$p) use ($methodName) {
            $this->{$methodName}(...$p);
        }, $this);
    }

    protected function exception($e)
    {
        if (method_exists($this, 'getHttpCode'))
            http_response_code($e->getHttpCode());
        else
            http_response_code(500);

        ResponseHandler
            ::getInstance()
            ->addError(
                false,
                get_class($e),
                $e->getMessage(),
                $e->getFile(),
                $e->getline(),
                $e->getTrace()
            );
    }

    protected function recoverable_error(
        int $no,
        string $message,
        string $file,
        int $line
    ) {
        ResponseHandler
            ::getInstance()
            ->addError(
                true,
                self::ERRORS[$no],
                $message,
                $file,
                $line,
                []
            );
    }
}