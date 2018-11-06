<?php

namespace Helper;

class PrepareException extends \Core\Exception\HttpException
{
    function __construct($exceptions)
    {
        $this->code = [];
        foreach ($exceptions as $key => $exception)
            $this->code[$key][] = "{$exception}";
    }
}

class Prepare
{
    protected $data;
    protected $ckeck = [];
    protected $ckeckFunctions = [];
    protected $errors = [];
    protected $result = [];

    private function loadType()
    {
        if ($this->ckeckFunctions[$type])
            return $this->ckeckFunctions[$type];

        if (file_exists($file = __DIR__ . "/Prepare/{$type}.php"))
            require $this->ckeckFunctions[$type] = require __DIR__ . "/Prepare/{$type}.php";
    }

    static function verify($data)
    {
        return new self($data);
    }

    function __construct($data)
    {
        $this->data = $data;
    }

    function check(string $target, string $key, $alias, string $type, ...$p)
    {
        $that = $this;
        $targetArr = (Array)$this->data->{$target};
        $value = $targetArr[$key] ?? null;
        $alias = $alias ?? $key;
        $classNameSpace = "\\" . __NAMESPACE__ . "\\Prepare\\Type" . ucfirst($type);
        $typeObj = new $classNameSpace($key, $alias, $value, ...$p);

        if (!($typeObj instanceof \Helper\Prepare\Type))
            $this->errors[$key] = "Prepare types needs to be extends Helper\Prepare\Type, type `{$type}` does not extends Helper\Prepare\Type.";

        try {
            $this->result[$alias] = $typeObj->getValue();
        } catch (\Exception $e) {
            $this->errors[$key] = $e->getMessage();
        }

        return $this;
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
        if (!empty($this->errors))
            throw new PrepareException($this->errors);
        return (Object)$this->result;
    }

}