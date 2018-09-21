<?php 

namespace helper;

class Select 
{
    private $table   = null;
    private $page    = 1;
    private $limit   = 100;
    private $query   = [];
    private $columns = [];

    private function trustedSqlName($name, $str)
    {
        if (preg_match('/[\s;]/', $str))
            throw new \system\exception\NotTrustedSqlName($name, $str);
        return $str;
    }

    function __construct($table)
    {
        $this->table = $this->trustedSqlName('table', $table);
    }

    function setColumns(... $columns)
    {
        foreach($columns as $column) {
            $this->columns[] = $this->trustedSqlName('column', $column);
        }
        return $this;
    }

    function setPage($page)
    {
        $page = (int)$page;
        $this->page = ($page < 1) ? 1 : $page;
        return $this;    
    }

    function setLimit($limit)
    {
        $limit = (int)$limit;
        $this->limit = ($limit < 1) ? 1 : $limit;
        return $this;    
    }

    function setQuery($queries)
    {
        foreach($queries as $key=>$val) {
            $this->query[] = "$key=:$key";
        }
        return $this;
    }

    function set()
    {
        return $this;    
    }

    function fetch()
    {
        $table   = $this->table;
        $columns = empty($this->columns) ? '*' : implode(', ', $this->columns);
        $where   = empty($this->query) ? '1' : implode(' AND ', $this->query);
        $offset  = $this->limit * ($this->page - 1);
        $limit   = $this->limit;
        echo "SELECT {$columns} FROM {$table} WHERE {$where} LIMIT {$offset},{$limit}";
    }

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