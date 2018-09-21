<?php 

namespace helper;

class Select 
{


    // private $limit = 100;
    // private $page = 1;

    // public function preventFunnyPeople($str)
    // {
    //     if (preg_match('/[\s;]/', $str))
    //         throw new \system\exception\NotTrustedSqlName($str);

    //     return $str;
    // }

    // public function setPage($p)
    // {
    //     if (!is_int($p))
    //         throw new InvalidArgumentException("first argument needs to be integer, `{$p}` given.");

    //     if ($p < 1)
    //         $this->page = 1;

    //     $this->page = $p;

    //     return $this;
    // }

    // public function select($table, $page, $limit, $column, ... $columns)
    // {
    //     global $ambue;
        
    //     array_unshift($columns, $column);
        
    //     $columnsQuery = [];
    //     $table = $this->preventFunnyPeople($table);
    //     $where = [];

    //     foreach($columns as $col) 
    //         $columnsQuery[] = $this->preventFunnyPeople($col);

    //     foreach($ambue->input->search as $key => $val)
    //         $where[] = $this->preventFunnyPeople($key) . "=:$key";
        
    //     if (empty($where))
    //         throw new \system\exception\NoSearchValuesFound();

    //     $columnsQuery = implode(', ', $columnsQuery);
    //     $where = implode(' AND ', $where);
    //     $offset = ($this->page-1) * $this->limit;
        
    //     echo "SELECT {$table} FROM $columnsQuery WHERE $where LIMIT {$offset}, {$this->limit}";
        
    //     return $this;
    // }
};