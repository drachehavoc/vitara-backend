<?php

namespace Helper\Relational\Map;

class Condition
{
    protected $columnsNames = [];
    protected $parts = [];
    protected $values = [];
    protected $uid = 0;

    function __construct(string $col, string $cond, $val, ... $vals)
    {
        $this->set($col, $cond, $val, $vals);
    }

    function and(string $col, string $cond, $val, ... $vals)
    {
        $this->parts[] = PHP_EOL . "    AND";
        return $this->set($col, $cond, $val, $vals);
    }
    
    function or(string $col, string $cond, $val, ... $vals)
    {
        $this->parts[] = PHP_EOL . "    OR ";
        return $this->set($col, $cond, $val, $vals);
    }

    function __toString()
    {
        return implode(" ", $this->parts);
    }

    function getValues()
    {
        return $this->values;
    }

    private function set(string $col, string $cond, $val, $vals)
    {
        $col = checkColumnName($col);
        switch ($cond)
        {
            case 'like':
            case '<>':
            case '>=':
            case '<=':
            case '>':
            case '<':
            case '=':
                return $this->setSimple($col, $cond, $val);
                
            case 'between':
                if (count($vals) < 1)
                    throw new \Exception('between precisa ter valor inicial e final');
                return $this->setBetween($col, $val, $vals[0]);

            case 'in':
                array_unshift($vals, $val);
                return $this->setIn($col, $vals);
        }
    }

    private function setSimple(string $col, string $cond, $val)
    {
        $uid = $this->uid++;
        $anchor = "{$col}_{$uid}";
        $this->parts[] = "{$col} {$cond} :{$anchor}";
        $this->values[$anchor] = $val;
        return $this;
    }

    private function setBetween(string $col, $min, $max)
    {
        $uid = $this->uid++;
        $anchor1 = "{$col}_{$uid}";
        
        $uid = $this->uid++;
        $anchor2 = "{$col}_{$uid}";

        $this->parts[] = "{$col} between :{$anchor1} and :{$anchor2}";
        $this->values[$anchor1] = $min;
        $this->values[$anchor2] = $max;
        return $this;
    }
    
    private function setIn(string $col, $vals)
    {
        $ins = [];
        foreach($vals as $val)
        {
            $uid = $this->uid++;
            $anchor = "{$col}_{$uid}";
            $ins[] = ":{$anchor}";
            $this->values[$anchor] = $val;
        }
        $this->parts[] = "{$col} in (". implode(', ', $ins) .")";
        return $this;
    }
}