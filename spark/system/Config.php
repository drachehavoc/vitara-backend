<?php

class Config
{
    protected static $instance = null;

    protected $data;
    protected $mergedData;
    protected $loadedFiles = [];

    static function getInstance()
    {
        return self::$instance ?? self::$instance = new self();
    }

    protected function __construct()
    {
        $this->data =
            $this->mergedData = [];
    }

    protected function getKey($cur, $key)
    {
        if (is_string($key))
            return $this->getKeyStringOrInteger($cur, $key);

        if (is_integer($key))
            return $this->getKeyStringOrInteger($cur, $key);

        if (is_array($key))
            return $this->getKeyArray($cur, $key);

        throw new \Exception('Chaves só podem ser do tipo string e array, foi recebido um ' . gettype($key));
    }

    protected function getKeyStringOrInteger($cur, $key)
    {
        if (array_key_exists($key, $cur))
            return is_object($cur) ? $cur->{$key} : $cur[$key];
        throw new \Exception('Chave não encontrada na configuração');
    }

    protected function getKeyArray($cur, $keys)
    {

        foreach ($keys as $key) {
            try {
                $cur = $this->getKey($cur, $key);
                return $cur;
            } catch (\Exception $e) {

            }
        }
        throw new \Exception('Nenhuma das chaves alternativas foi encontrada');
    }

    function get(...$keys)
    {
        if (is_null($keys[0])) {
            $cur = $this->mergedData;
            array_shift($keys);
        } else {
            $cur = $this->data;
        }

        foreach ($keys as $key)
            $cur = $this->getKey($cur, $key);

        return $cur;
    }

    function set(array $data, string $alias = 'default')
    {
        $this->data[$alias] = $data;
        $this->mergedData = array_replace_recursive($this->mergedData, $data);
        return $this;
    }

    function load(string $filePath, string $alias = 'default')
    {
        if (!file_exists($filePath))
            throw new \Exception("Configuration file `{$filePath}` not found");

        $file = include $filePath;

        if (!is_array($file))
            throw new \Exception("Configuration file `{$filePath}` is not an array");

        $this->loadedFiles[] = $filePath;

        $this->set($file, $alias);

        return $this;
    }

    function getLoadedFiles()
    {
        return $this->loadedFiles;
    }
}