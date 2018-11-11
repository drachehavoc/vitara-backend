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
        array_unshift($datas, $data);
        foreach ($datas as $curr) {
            if ($curr !== null)
                $this->responses[] = $curr;
        }
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
            utf8_encode($message),
        ] : [
            $type,
            utf8_encode($message),
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

        if (json_last_error()) {
            http_response_code(500);
            print_r(["erro ao montar retorno", json_last_error(), json_last_error_msg()]);
        }
    }
}