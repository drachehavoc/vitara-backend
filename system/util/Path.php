<?php

namespace system\util;

class Path {
    static function join(... $p)
    {
        return implode(DIRECTORY_SEPARATOR, $p);
    }

    static function resolve(... $p)
    {
        $file = Path::join(... $p);
        if (file_exists($file)) return realpath($file);
        throw new \system\exception\FileNotFound($file);
    }

    static function require (... $p) 
    {
        return require Path::resolve(... $p);
    }

    static function requireFunction($newThis = null, $newStatic = null, ... $p)
    {
        $func = Path::require(... $p);
        
        if ( is_callable($func) )
            return \Closure::bind($func, $newThis, $newStatic);
        
        throw new \system\exception\FunctionNotFound(Path::join(... $p));
    }

    static function firstFound(... $files)
    {
        forEach($files as $file) 
            if (file_exists($file))
                return $file;
        throw new \system\exception\FilesNotFound($files);
    }
}