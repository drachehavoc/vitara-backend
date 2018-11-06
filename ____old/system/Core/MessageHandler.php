<?php

namespace Core;

class ErrorHandler
{
    static private $instance = null;

    static function getInstance()
    {
        return Self::$instance
            ? Self::$instance
            : Self::$instance = new Self();
    }

    const NAME = [
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
        32767 => "E_ALL",
    ];

    private $trace = [];

    private function __construct()
    {
        // set_error_handler([$this, 'error']);
    }

    function exception(\Exception $e)
    {
        global $root;
        $this->trace['exception'][] = !$root['override']['production'] ? [
            'message' => $e->getMessage(),
            'type' => get_class($e),
            'step' => $root['step'],
            'detail' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTrace()
        ] : [
            'message' => $e->getMessage(),
            'step' => $root['step'],
            'type' => get_class($e),
            'detail' => $e->getCode()
        ];
    }

    function error($no, $message, $file, $line)
    {
        global $root;
        $type = ltrim(ErrorHandler::NAME[$no], 'E_');
        $this->trace['error'][] = !$root['override']['production'] ? [
            'message' => $message,
            'step' => $root['step'],
            'type' => $type,
            'detail' => $no,
            'file' => $file,
            'line' => $line
        ] : [
            'message' => $message,
            'step' => $root['step'],
            'type' => $type,
            'detail' => $no
        ];
    }

    function getTrace()
    {
        return $this->trace;
    }
}