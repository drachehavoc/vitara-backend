<?php

class ResponseHandler
{
    protected static $instance = null;
    protected $responses = [];
    protected $errors = [];
    static function getInstance()
    {
        return self::$instance ?? self::$instance = new self();
    }

    function addResponse($data)
    {
        $this->responses[] = $data;
        return $this;
    }

    function addError(
        bool $recoverable,
        string $type,
        string $message,
        string $file,
        int $line,
        array $trace
    ) {
        try {
            $production = Config::getInstance()->get(null, 'production');
        } catch (Exception $e) {
            $production = false;
        }

        $this->errors[] = $production ? [
            $type,
            $message,
        ] : [
            $type,
            $message,
            $file,
            $line,
            $trace
        ];

        if ($recoverable === false)
            $this->response();
    }

    function response()
    {
        print_r([
            'response' => $this->responses,
            'errors' => empty($this->errors) ? null : $this->errors
        ]);
    }
}