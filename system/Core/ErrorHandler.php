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
        1     => "E_ERROR",
        2     => "E_WARNING",
        4     => "E_PARSE",
        8     => "E_NOTICE",
        16    => "E_CORE_ERROR",
        32    => "E_CORE_WARNING",
        64    => "E_COMPILE_ERROR",
        128   => "E_COMPILE_WARNING",
        256   => "E_USER_ERROR",
        512   => "E_USER_WARNING",
        1024  => "E_USER_NOTICE",
        2048  => "E_STRICT",
        4096  => "E_RECOVERABLE_ERROR",
        8192  => "E_DEPRECATED",
        16384 => "E_USER_DEPRECATED",
        32767 => "E_ALL",
    ];

    private $trace = [];

    private function __construct(){
        set_error_handler([$this, 'error']);
    }
    
    function exception(\Exception $e)
    {
        $this->trace['exception'][] = !\APPLICATION\PRODUCTION
            ? [ "message" => $e->getMessage(),
                "type"    => get_class($e),
                "detail"  => $e->getCode(),
                "file"    => $e->getFile(),
                "line"    => $e->getLine(),
                "trace"   => $e->getTrace()]

            : [ "message" => $e->getMessage(),
                "type"    => get_class($e),
                "detail"  => $e->getCode()];
    }

    function error($no, $message, $file, $line)
    {
        $type = ErrorHandler::NAME[ $no ];
        $this->trace['error'][] = !\APPLICATION\PRODUCTION
            ? [ "message" => $message,
                "type"    => $type,
                "detail"  => $no,
                "file"    => $file,
                "line"    => $line]
            
            : [ "message" => $message,
                "type"    => $type,
                "detail"  => $no];


            $type = ltrim($type, 'E_');
            error_log("{$type} {$message} in {$file} on line {$line}");
    }

    function getTrace()
    {
        return $this->trace;
    }
}