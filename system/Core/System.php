<?php

namespace Core;

use Core\Util\Path;

class System
{
    private $response = [];
    private $errorHandler;

    static private $instance = null;

    static function getInstance()
    {
        return Self::$instance ? Self::$instance : Self::$instance = new Self();
    }


    private function __construct()
    {
        global $root;

        $root['helpers']['system'] = \SYSTEM\HOME . 'Helpers' . DS;
        $root['system'] = [
            'home' => \SYSTEM\HOME,
            'production' => false,
            'timezone' => date_default_timezone_get(),
            'logs' => HOME . '[[logs]]' . DS
        ];
        $root['override'] = array_replace_recursive($root['override'], $root['system']);

        // ---------------------------------------------------------------------

        $this->errorHandler = ErrorHandler::getInstance();

        try {
            $this->configurePHP();
            $this->gate();
        } catch (\Exception $e) {
            $this->errorHandler->exception($e);
        }

        $this->response();
    }

    private function configurePHP()
    {
        global $root;

        header('Content-Type: application/json');
        date_default_timezone_set($root['override']['timezone']);

        is_dir($logs = HOME . 'logs' . DS . HOST . DS)
            or mkdir($logs, 0755, true);

        ini_set('display_errors', true);
        ini_set('log_errors', true);
        ini_set('error_log', $logs . 'php-errors.log');
        ini_set('log_errors_max_len', 1024);
    }

    private function gate()
    {
        global $root;

        $home = HOME . APPLICATIONS . DS;
        $root['helpers']['gate'] = $home . '[[helpers]]' . DS;
        $root['gate'] = $config = Path::loadArray($home . CONFIGURATION, ['gates' => []]);
        $root['override'] = array_replace_recursive($root['override'], $config);

        if (!array_key_exists(HOST, $config['gates']))
            throw new \Exception('Gate não definido para o host `' . HOST . '`'); // @todo: melhorar

        $target = $config['gates'][HOST];

        if (is_callable($target)) {
            return $this->response = \CLosure::bind($target, $root['context'])();
        }

        if (is_string($target))
            return $this->response = $this->route($home . $target . DS);

        throw new \Exception("Valor inválido para gates `" . HOST . "` só pode conter valores do tipo string ou function.");
    }

    private function route($home)
    {
        global $root;

        $root['helpers']['route'] = $home . '[[helpers]]' . DS;
        $root['route'] = Path::loadArray($home . CONFIGURATION, ['routes' => []]);
        $root['override'] = array_replace_recursive($root['override'], $config);
        
        // ---------------------------------------------------------------------

        $endpoint = null;

        foreach ($config['routes'] as $regex => $target) {
            if (!preg_match($regex, $root['context']->path, $matches))
                continue;

            if (is_string($target))
                $endpoint = Path::loadFunction($home . "{$target}.php", $root['context']);

            if (is_callable($target))
                $endpoint = \Closure::bind($target, $root['context']);

            $root['context']->matches = $matches;

            break;
        }

        if (!$endpoint)
            throw new \Core\Exception\EndPointNotFound();

        return $endpoint();
    }

    private function response()
    {
        $response = ['response' => $this->response];

        $errors = $this->errorHandler->getTrace();

        if (!empty($errors)) {
            http_response_code(500);
            $response = array_merge($response, $errors);
        }

        echo json_encode($response);
    }
}