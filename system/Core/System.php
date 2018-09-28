<?php

namespace Core;

use Core\Util\Path;

class System
{
    function __construct()
    {   
        $this->configurePHP();
        $this->route();
    }

    private function configurePHP()
    {
        date_default_timezone_set(\APPLICATION\TIMEZONE);
        ini_set('display_errors', !\APPLICATION\PRODUCTION);
    }

    private function route()
    {
        $endpoint = null;

        $ctx = (Object)[
            "path"    => $_SERVER['REQUEST_METHOD'] . ($_SERVER['PATH_INFO'] ?? $_SERVER['PHP_SELF']),
            "query"   => (Object)$_GET,
            "payload" => json_decode( file_get_contents('php://input') ),
            "matches" => null
        ];
        
        foreach(\APPLICATION\ROUTES as $regex => $filename)
        {
            if (preg_match($regex, $ctx->path, $matches))
            {
                $endpoint = Path::loadFunction(\APPLICATION\HOME."{$filename}.php", $ctx);
                $ctx->matches = $matches;
                break;
            }
        }

        if (!$endpoint) 
            throw new \Core\Exception\EndPointNotFound();
        
        die( json_encode([ "reponse" => $endpoint() ]) );
    }
}