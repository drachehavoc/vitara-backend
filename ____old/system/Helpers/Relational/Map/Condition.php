<?php

namespace Helper\Relational\Map;

class Condition
{
    protected $parts = [];
    protected $values;
    protected $count = 0;

    function __construct(string $col, string $cond, $val, ... $vals)
    {
        $this->values = new Values('w');
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
        $vals = array_merge((Array)$val, $vals);
        $this->values->set($col, $vals, [
            'cond' => $cond,
            'glue' => $glue
        ]);
        return $this;
    }

    function mount()
    {
        $query = [];
        $values = [];
        
        foreach($this->values->mount() as $key => $part)
        {
            switch ($part['payload']['cond'])
            {
                case '=':
                case '>':
                case '<':
                case '>=':
                case '<=':
                case '<>':
                case 'like':
                    $values[$key] = ((Array)$part['value'])[0];
                    $query[] = "{$part['payload']['glue']}{$part['column']} {$part['payload']['cond']} :{$key}";
                    break;
                    
                case 'in':
                    $keys = [];
                    foreach($part['value'] as $keyIndex => $value) 
                    {
                        $keyIndex = "{$key}{$keyIndex}_";
                        $keys[] = ":{$keyIndex}";
                        $values[ $keyIndex ] = $value;
                    }
                    $query[] = "{$part['payload']['glue']}{$part['column']} in (".implode(", ", $keys).")";
                    break;
                
                case 'between':
                    if (!isset($part['value'][1]))
                        throw new \Exception("between precisa ter dois parâmetros <--- melhorar"); 
                    $values[$key.'0_'] = $part['value'][0];
                    $values[$key.'1_'] = $part['value'][1];
                    $query[] = "{$part['payload']['glue']}{$part['column']} between :{$key}0_ and :{$key}1_";
                    break;
            }
        }

        return (Object)[
            'query' => implode(' ', $query),
            'values' => $values
        ];
    }
}