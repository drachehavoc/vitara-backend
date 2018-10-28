<?php

class Autoload
{
    static private $instance = null;

    protected $paths = [HOME];

    static function getInstance()
    {
        return Self::$instance ?? Self::$instance = new Self();
    }

    protected function __construct()
    {
        spl_autoload_register([$this, 'trigger']);
    }


    protected function trigger($namespace)
    {
        foreach ($this->paths as $path) {
            if (file_exists($file = $path . DS . $namespace . '.php')) {
                return require $file;
            }
        }
    }

    public function addPath(string $path)
    {
        if (!is_dir($path))
            throw new \Exception("A pasta `$path` não foi encontrado, este caminho não pode ser adicionado na lista de path do Autoload");

        array_unshift($this->paths, $path . DS);
        $this->paths[] = array_unique($this->paths);

        return $this;
    }
}