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
        $this->response = \Core\ResponseHandler::getInstance();
    }

    function add(string $regex, $target, ...$targets)
    {
        array_unshift($targets, $target);
        $this->routes[$regex] = $targets;
        return $this;
    }

    private function getTargetsValues($matches, $targets)
    {
        $result = [];
        
        $obj = (Object)[
            'matches' => $matches
        ];

        foreach ($targets as $target) {
            if (is_string($target)) {
                if (!file_exists($file = $target))
                    throw new \Exception("Arquivo `${file}` não foi encontrado");

                $fn = require $file;

                if ($fn instanceof \Closure) {
                    $result[] = \Closure::bind($fn, $obj)($obj);
                    continue;
                }

                throw new \Exception("Arquivo `${file}` não retorna uma função");
            }
            
            if ($target instanceof \Closure) {
                $result[] = \Closure::bind($target, $obj)($obj);
                continue;
            }
            
            throw new \Exception("Rotas devem ser funções ou caminhos para arquivos que contenham funções");
        }

        return $result;
    }

    function go(bool $findAll = true)
    {
        $map = $_SERVER['REQUEST_METHOD'] . ($_SERVER['PATH_INFO'] ?? $_SERVER['PHP_SELF']);
        foreach ($this->routes as $regex => $targets) {
            if (preg_match($regex, $map, $matches)) {
                $targetsValue = $this->getTargetsValues($matches, $targets);
                $this->response->addResponse(... $targetsValue);
                if (!$findAll)
                    break;
            }
        }
    }
}