<?php

namespace Helper\Relational\Map;

const ANCHOR = \Helper\Relational\Map::ANCHOR;

class Values {
    protected $map           = [];
    protected $anchorsValues = [];
    protected $counter       = 0;

    private function key()
    {
        $this->counter++;
        return "val_{$this->counter}_";
    }

    function add($value, ... $values)
    {
        return $this->addWithConfig(null, $value, ... $values);
    }

    function addWithConfig($conf, $value, ... $values)
    {
        $key = $this->key();
        $this->map[ $key ]["config"] = $conf; 
        ($value === ANCHOR && isset($values[0])) 
            ? $this->map[ $key ][ 'anchor'] = $values[0] 
            : $this->map[ $key ][ 'value' ] = array_merge((Array)$value, $values) ;
        return $key;
    }

    function anchors(Array $anchors)
    {
        $this->anchorsValues = $anchors;
    }

    /**
     * monta array com valores preenchidos, substituindo ancoras se necessário
     */
    function mount($simple=false)
    {
        $errors = [];
        $values = [];
        $valuesSimple = [];

        // monta array de valores
        foreach($this->map as $key => $item)
        {
            // monta valores ancora
            if (isset($item['anchor'])) 
            {
                if (!isset($this->anchorsValues[ $item['anchor'] ]) )
                {
                    $errors[] = $item['anchor'];
                    continue;
                }
                $values[$key]['config'] = $item['config'];
                $values[$key]['value'] = (Array)$this->anchorsValues[ $item['anchor'] ];

            } else {
                // monta valores fixos
                $values[$key] = $item;
            }
            
            //
            $valuesSimple[$key] = $values[$key]['value'][0];
        }

        if (!empty($errors))
            throw new \Exception("não foram definidos valores para as seguintes ancoras: ". implode(', ', $errors) ." <--- melhorar");

        return $simple ? $valuesSimple : $values;
    }

    function mountSimple()
    {
        return $this->mount(true);
    }
}