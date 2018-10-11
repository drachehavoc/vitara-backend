<?php

namespace Helper\Relational\Map;

class Condition
{
    protected $parts = [];
    protected $values;
    protected $count = 0;

    function __construct(string $col, string $cond, $val, ... $vals)
    {
        $this->values = new Values();
        $this->set('', $col, $cond, $val, ... $vals);
    }

    function and($col, $cond, $val, ... $vals)
    {
        return $this->set('AND ', $col, $cond, $val, ... $vals);
    } 

    function or($col, $cond, $val, ... $vals)
    {
        return $this->set('OR ', $col, $cond, $val, ... $vals);
    }

    function anchors($anchors)
    {
        $this->values->anchors((Array)$anchors);
        return $this;
    }
    
    private function set($glue, $col, $cond, $val, ... $vals)
    {
        $refVal = $this->values->add($val, ... $vals);
        $this->parts[] = [
            "glue" => $glue,
            "column" => Check::columnName($col),
            "condition" => $cond,
            "reference-value" => $refVal
        ];
        return $this;
    }

    private function unique()
    {
        $this->count++;
        return ":val_{$this->count}_";
    }

    function mount()
    {
        $mntQuery = [];
        $mntValues = [];
        $queryValues = $this->values->mount();
        
        foreach($this->parts as $part)
        {
            $refKey     = $part["reference-value"];
            $mnt        = $this->mountCheckType($part, $queryValues[ $refKey ]);
            $mntQuery[] = $mnt['query'];
            $mntValues  = array_merge($mntValues, $mnt['values']); 
        }

        return (Object)[
            'query'  => implode(' ', $mntQuery),
            'values' => $mntValues
        ];
    }

    private function mountCheckType($part, $value)
    {
        switch ($part['condition'])
        {
            case '=':
            case '>':
            case '<':
            case '>=':
            case '<=':
            case '<>':
            case 'like':
                return $this->mountCommon($part, $value);
                
            case 'in':
                return $this->mountIn($part, $value);
            
            case 'between':
                return $this->mountBetween($part, $value);
        }
    }

    private function mountCommon($part, $value)
    {
        $anch = $this->unique();
        return [
            "query"  => "{$part['glue']}{$part['column']} {$part['condition']} {$anch}",
            "values" => [$anch => $value[0]]
        ];
    }

    private function mountBetween($part, $value)
    {
        $anch1 = $this->unique();
        $anch2 = $this->unique();
        return [
            "query"  => "{$part['glue']}{$part['column']} between {$anch1} and {$anch2}",
            "values" => [
                $anch1 => $value[0],
                $anch2 => $value[1]
            ]
        ];
    }

    private function mountIn($part, $values)
    {
        $mntValues = [];
        $mntAnchs = [];
        foreach($values as $value)
        {
            $anch = $this->unique();
            $mntAnchs[] = $anch;
            $mntValues[$anch] = $value;
        }
        return [
            "query"  => "{$part['glue']}{$part['column']} in (". implode(', ', $mntAnchs) .")",
            "values" => $mntValues
        ];
    }
}