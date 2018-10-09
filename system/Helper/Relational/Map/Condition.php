<?php

namespace Helper\Relational\Map;

class Condition
{

    protected $columnsNames = [];
    protected $parts = [];
    protected $values = [];
    protected $anchors = [];
    protected $ident = "    ";

    function __construct(string $col, string $cond, $val, ... $vals)
    {
        $this->set(null, $col, $cond, $val, ... $vals);
    }

    private function set($glue, $col, $cond, $val, ... $vals)
    {
        array_unshift($vals, $val);
        $this->parts[] = [
            "col"  => $col,
            "cond" => $cond,
            "glue" => $glue ? $glue : "",
            "vals" => $vals
        ];
        return $this;
    }

    private function createAnchor($value)
    {
        $name = ":val" . count($this->values) . "_";
        $this->values[$name] = $value;
        return $name;
    }

    private function common($part)
    {
        $anchorName = $this->createAnchor($part['vals'][0]);
        return "{$part['glue']}{$part['col']} {$part['cond']} {$anchorName}";
    } 

    private function in($part)
    {
        $anchorNames = [];
        foreach($part['vals'] as $val)
            $anchorNames[] = $this->createAnchor($val);
        return "{$part['glue']}{$part['col']} in (". implode(", ", $anchorNames) .")";
    } 

    private function between($part)
    {
        if (count($part['vals']) < 2)
            throw new \Exception("a clausula 'between' precisa receber exatos dois parâmetros, recebido '". implode(", ", $part['vals']) ."' <--- melhorar");
        $anchorName1 = $this->createAnchor($part['vals'][0]);
        $anchorName2 = $this->createAnchor($part['vals'][1]);
        return "{$part['glue']}{$part['col']} between {$anchorName1} and {$anchorName2}";
    }

    function and($col, $cond, $val, ... $vals)
    {
        return $this->set($this->ident.'AND ', $col, $cond, $val, ... $vals);
    } 

    function or($col, $cond, $val, ... $vals)
    {
        return $this->set($this->ident.' OR ', $col, $cond, $val, ... $vals);
    } 

    function setAnchors($anchors)
    {
        $this->values = [];
        $this->anchors = (Array)$anchors;
        return $this;
    }

    function getPDOParams()
    {
        $query = [];

        foreach($this->parts as $part)
        {
            if ($part['vals'][0] === ANCHOR)
            {
                $anchorName = $part['vals'][1];
                if (!isset($this->anchors[ $anchorName ]))
                    throw new \Exception("ancora '$anchorName' não foi definida <--- melhorar");
                $part['vals'] = $this->anchors[ $anchorName ];
            }

            switch ($part['cond'])
            {
                case 'like':
                case '<>':
                case '>=':
                case '<=':
                case '>':
                case '<':
                case '=':
                    $query[] = $this->common($part);
                    break;

                case 'between':
                    $query[] = $this->between($part);
                    break;

                case 'in':
                    $query[] = $this->in($part);
                    break;
            }
        }

        return (Object)[
            "query" => implode(PHP_EOL, $query),
            "values" => $this->values
        ];
    }
}