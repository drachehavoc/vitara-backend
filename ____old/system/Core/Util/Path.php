<?php

namespace Core\Util;

class Path
{
    static function join(string ...$parts)
    {
        return implode(DIRECTORY_SEPARATOR, $parts);
    }

    static function findIn(string $file, string $folder, string ...$folders)
    {
        array_unshift($folders, $folder);
        foreach ($folders as $folder)
            if (file_exists($find = Self::join($folder, $file)))
            return $find;
        return null;
    }

    static function requireFile(string $file)
    {
        if (file_exists($file))
            return require $file;

        throw new \Core\Exception\FileNotFound($file);
    }

    static function loadFunction(string $file, Object $ctx)
    {
        $function = Self::requireFile($file);

        if (is_callable($function))
            return \Closure::bind($function, $ctx, null);

        throw new \Core\Exception\FileDoesNotReturnAFunction($file);
    }

    static function loadArray(string $file, Array $default = null)
    {
        try {
            $array = Self::requireFile($file);
        } catch (\Core\Exception\FileNotFound $e) {
            if ($default)
                return $default;
            throw $e;
        }

        if (!is_array($array))
            throw new \Core\Exception\FileDoesNotReturnAArray($file);
            
        if ($default)
            $array = array_replace_recursive($default, $array);

        return $array;

    }
}