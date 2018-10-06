<?php

namespace Core;

use Core\Util\Path;

class System
{
    static private $instance = null;
    
    static function getInstance()
    {
        return Self::$instance ? Self::$instance : Self::$instance = new Self(); 
    }

    private $response = [];
    private $errorHandler;
    
    private function __construct()
    { 
        $this->errorHandler = ErrorHandler::getInstance();
        $this->configurePHP();

        try 
        {
            $this->route();
        } 
        
        catch (\Exception $e) 
        {
            $this->errorHandler->exception($e);
        } 
        
        finally
        {
            $this->response();
        }  
    }

    private function configurePHP()
    {
        header('Content-Type: application/json');
        date_default_timezone_set(\APPLICATION\TIMEZONE);
        ini_set('display_errors', !\APPLICATION\PRODUCTION);
        if (\APPLICATION\LOGS) 
        {
            is_dir(\APPLICATION\LOGS)
            or mkdir(\APPLICATION\LOGS, 0755, true);
            ini_set('log_errors', true); 
            ini_set('error_log', \APPLICATION\LOGS.'php-errors.log'); 
            ini_set('log_errors_max_len', 1024); 
        }
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
        
        $this->response = $endpoint();
    }

    private function response()
    {
        $errors = $this->errorHandler->getTrace();
        $response = [
            'response' => $this->response,
        ];

        if (!empty($errors)) 
            $response = array_merge($response, $errors);

        echo json_encode($response);
    }
}