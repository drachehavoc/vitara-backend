<?php

namespace Core;

class ResponseHandler
{
    protected static $instance = null;
    protected $responses = [];
    protected $errors = [];
    
    static function getInstance()
    {
        return self::$instance ?? self::$instance = new self();
    }

    function addResponse($data, ...$datas)
    {
        $this->responses[] = $data;
        foreach ($datas as $data)
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
        } catch (\Exception $e) {
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
        header('Content-Type: application/json');
        echo json_encode([
            'response' => $this->responses,
            'errors' => empty($this->errors) ? null : $this->errors
        ]);
    }
}