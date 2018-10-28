<?php 

namespace Helper;

class Route
{
    protected $routes = [];

    protected $response;

    static function createInstance()
    {
        return new self();
    }

    function __construct()
    {
        $this->response = \ResponseHandler::getInstance();
    }

    function add(string $regex, $target)
    {
        $this->routes[$regex] = $target;
        return $this;
    }

    function go(bool $findAll = true)
    {
        $map = $_SERVER['REQUEST_METHOD'] . ($_SERVER['PATH_INFO'] ?? $_SERVER['PHP_SELF']);
        foreach ($this->routes as $regex => $target) {
            if (preg_match($regex, $map, $matches)) {
                if ($target instanceof \Closure)
                    $target = $target();
                $this->response->addResponse($target);
                if (!$findAll)
                    break;
            }
        }
    }
}