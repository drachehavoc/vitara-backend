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
        $root['step'] = 'system';
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
        } catch (\Core\Exception\HttpException $e) {
            http_response_code($e->getHttpStatus());
            $this->errorHandler->exception($e);
        } catch (\Exception $e) {
            http_response_code(500);
            $this->errorHandler->exception($e);
        }

        $this->response();
    }

    private function setRoot($key, $home)
    {
        global $root;
        $root['step'] = $key;
        $root['helpers'][$key] = $home . '[[helpers]]' . DS;
        $root[$key] = Path::loadArray($home . CONFIGURATION, [
            'gates' => [],
            'routes' => [],
        ]);
        $root['override'] = array_replace_recursive($root['override'], $root[$key]);
        return $root;
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
        $home = HOME . APPLICATIONS . DS;
        $config = $this->setRoot('gate', $home);
        $gates = $config['gate']['gates'];
        $context = $config['context'];

        if (!array_key_exists(HOST, $gates))
            throw new \Core\Exception\GateNotFound();

        $target = $gates[HOST];

        if (is_callable($target))
            return $this->response = \CLosure::bind($target, $context)();

        if (is_string($target))
            return $this->response = $this->route($home . $target . DS);

        throw new \Exception("Valor inválido para gates `" . HOST . "` só pode conter valores do tipo string ou function.", 500); // @todo: melhorar
    }

    private function route($home)
    {
        global $root;
        $config = $this->setRoot('route', $home);
        $routes = $config['route']['routes'];
        $context = $config['context'];
        $endpoint = null;

        foreach ($routes as $regex => $target) {
            if (!preg_match($regex, $context->path, $matches))
                continue;

            if (is_string($target))
                $endpoint = Path::loadFunction($home . "{$target}.php", $context);

            if (is_callable($target))
                $endpoint = \Closure::bind($target, $context);


            break;
        }

        $root{'context'}->matches = $matches;
        if (!$endpoint)
            throw new \Core\Exception\EndPointNotFound();

        return $endpoint();
    }

    private function response()
    {
        $response = ['response' => $this->response];

        $errors = $this->errorHandler->getTrace();

        if (!empty($errors)) {
            $response = array_merge($response, $errors);
        }

        echo json_encode($response);
    }
}