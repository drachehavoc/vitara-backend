<?php

namespace Helper\Relational\Map;

class Values {
    protected $prefix = '';
    protected $columns = [];
    protected $anchorsValues = [];
    protected $data = [];
    protected $counter = 0;

    function __construct($prefix = null)
    {
        if ($prefix)
            $this->prefix = "{$prefix}_";
    }

    private function key() : string
    {
        $this->counter++;
        return "{$this->prefix}val_{$this->counter}_";
    }

    function define(string $columnName, $value, $payload = null) : Self
    {
        $key = $this->key();
        $this->columns[] = $columnName;
        $this->data[$key] = [
            'column'  => $columnName,
            'payload' => $payload,
            'value'   => $value,
        ];
        return $this;
    }
    
    function anchor(string $columnName, string $anchorName, $payload = null) : Self
    {
        $key = $this->key();
        $this->columns[] = $columnName;
        $this->data[$key] = [
            'column'  => $columnName,
            'payload' => $payload,
            'anchor'  => $anchorName
        ];
        return $this;
    }

    function set(string $columnName, $data, $payload = null)
    {
        return $data[0] === \Helper\Relational\Map::ANCHOR
            ? $this->anchor($columnName, $data[1], $payload)
            : $this->define($columnName, $data, $payload);
    }

    function anchors(Array $values) : Self
    {
        $this->anchorsValues = $values;
        return $this;
    }

    function mount(bool $simple = false) : Array
    {
        $result = [];
        $resultSimple = [];
        $errors = [];

        foreach($this->data as $key => $curr)
        {
            $result[ $key ] = $curr;

            if (array_key_exists('anchor', $curr))
            {
                $anchor = $curr['anchor'];
                if (array_key_exists($anchor, $this->anchorsValues)) 
                {
                    $result[ $key ]['value'] = $this->anchorsValues[$anchor];
                } else {
                    $errors[] = $anchor;
                    continue;
                }
            }

            $resultSimple[ $key ] = $result[ $key ]['value'];
        }

        if (!empty($errors))
            throw new \Exception("não foram definidos valores para as seguintes ancoras: ". implode(', ', $errors) ." <--- melhorar");

        return $simple ? $resultSimple : $result;
    }

    function mountSimple()
    {
        return $this->mount(true);
    }

    function columns() : Array
    {
        return array_unique($this->columns);
    }
}