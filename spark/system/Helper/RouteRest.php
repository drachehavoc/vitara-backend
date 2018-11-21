<?php 

namespace Helper;

class RouteRest
{
    function __construct($root)
    {
        \Helper\Route::createInstance()
            ->add("|^GET/{$root}/(?<id>[0-9])+$|i", $this->execute('getOne'))
            ->add("|^GET/{$root}$|i", $this->execute('getMany'))
            ->add("|^POST/{$root}$|i", $this->execute('post'))
            ->add("|^PUT/{$root}/(?<id>[0-9]+)$|i", $this->execute('put'))
            ->add("|^DELETE/{$root}/(?<id>[0-9]+)$|i", $this->execute('delete'))
            ->go(false);
    }

    function getMany()
    {
        throw new \Exception('not implemented');
    }

    function getOne(int $id)
    {
        throw new \Exception('not implemented');
    }

    function post()
    {
        throw new \Exception('not implemented');
    }

    function put(int $id)
    {
        throw new \Exception('not implemented');
    }

    function delete(int $id)
    {
        throw new \Exception('not implemented');
    }

    private function execute($name)
    {
        $that = $this;
        return function ($route) use ($that, $name) {
            $that->route = $route;
            return $that->{$name}((int)$route->matches['id']);
        };
    }
}