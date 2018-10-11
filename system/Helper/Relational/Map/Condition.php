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
        $conf = [
            "glue"      => $glue,
            "condition" => $cond,
            "column"    => $col
        ];

        $this->values->addWithConfig($conf, $val, ... $vals);
        return $this;
    }

    function mount()
    {
        $mntQuery = [];
        $mntValues = [];

        foreach($this->values->mount() as $key => $part)
        {
            $config = $part['config'];
            $partValues = $part['value'];

            switch ($config['condition'])
            {
                case '=':
                case '>':
                case '<':
                case '>=':
                case '<=':
                case '<>':
                case 'like':
                    $mntValues[$key] = $partValues[0];
                    $mntQuery[] = "{$config['glue']}{$config['column']} {$config['condition']} :{$key}";
                    break;
                    
                case 'in':
                    $keys = [];
                    foreach($partValues as $valueIndex => $value) 
                    {
                        $keys[] = ":".$key."{$valueIndex}_";
                        $mntValues[$key."{$valueIndex}_"] = $value;
                    }
                    $mntQuery[] = "{$config['glue']}{$config['column']} in (".implode(", ", $keys).")";
                    break;
                
                case 'between':
                    if (!isset($partValues[1]))
                        throw new \Exception("between precisa ter dois parâmetros <--- melhorar"); 
                    $mntValues[$key.'A_'] = $partValues[0];
                    $mntValues[$key.'B_'] = $partValues[1];
                    $mntQuery[] = "{$config['glue']}{$config['column']} between :{$key}_A_ and :{$key}_B_";
                    break;
            }
        }

        return (Object)[
            'query'  => implode(' ', $mntQuery),
            'values' => $mntValues
        ];
    }
}