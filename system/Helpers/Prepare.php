<?php

namespace Helper;

class PrepareException extends \Core\Exception\HttpException
{
    function __construct($exceptions)
    {
        $this->message = [];
        foreach ($exceptions as $key => $exception)
            $this->message[$key] = "{$exception}";
        // $this->message = implode(', \n', $messages);
    }
}

class Prepare
{
    protected $data;
    protected $ckeck = [];
    protected $ckeckFunctions = [];
    protected $exeptions = [];
    protected $result = [];

    private function loadType()
    {
        if ($this->ckeckFunctions[$type])
            return $this->ckeckFunctions[$type];

        if (file_exists($file = __DIR__ . "/Prepare/{$type}.php"))
            require $this->ckeckFunctions[$type] = require __DIR__ . "/Prepare/{$type}.php";
    }

    function __construct($data)
    {
        $this->data = $data;
    }

    function check(string $target, string $key, $alias, string $type, ...$p)
    {
        $that = $this;
        $value = $this->data->{$target}->{$key} ?? $this->data->{$target}[$key] ?? null;
        $alias = $alias ?? $key;
        $classNameSpace = "\\" . __NAMESPACE__ . "\\Prepare\\Type" . ucfirst($type);

        try {
            $typeObj = new $classNameSpace($value, ...$p);
            if (!($typeObj instanceof \Helper\Prepare\Type))
                throw new \Exception("Prepare types needs to be extends Helper\Prepare\Type, type `{$type}` does not extends Helper\Prepare\Type.");
            $this->result[$alias] = $typeObj->getValue();
        } catch (\Exception $e) {
            $this->exeptions[$key] = $e->getMessage();
        }

        return function (string $key, $alias, string $type, ...$p) use ($that, $target) {
            return $that->check($target, $key, $alias, $type, ...$p);
        };
    }

    function payload(string $key, $alias, string $type, ...$p)
    {
        return $this->check('payload', $key, $alias, $type, ...$p);
    }

    function query(string $key, $alias, string $type, ...$p)
    {
        return $this->check('query', $key, $alias, $type, ...$p);
    }

    function matches(string $key, $alias, string $type, ...$p)
    {
        return $this->check('matches', $key, $alias, $type, ...$p);
    }

    function getData()
    {
        if (!empty($this->exeptions))
            throw new PrepareException($this->exeptions);
        return (Object)$this->result;
    }

}