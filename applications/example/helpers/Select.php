<?php 

namespace helper;

class Select 
{
    private $pdo         = null;
    private $table       = "no-table-defined";
    private $page        = 1;
    private $limit       = 100;
    private $query       = [];
    private $queryValues = [];
    private $columns     = [];
    private $forEachFns  = [];
    private $subSelects  = [];

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function trustedSqlName($name, $str)
    {
        if (preg_match('/[\s;]/', $str))
            throw new \system\exception\NotTrustedSqlName($name, $str);
        return $str;
    }

    function setTable($table)
    {
        $this->table = $this->trustedSqlName('table', $table);
        return $this;
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
        $this->queryValues = $queries;
        foreach($queries as $key=>$val) {
            $this->query[] = "$key=:$key";
        }
        return $this;
    }

    function set()
    {
        return $this;    
    }

    function __invoke($table)
    {
        return $this->setTable($table);
    }

    function forEach($columnName, $function)
    {
        $this->forEachFns[$columnName] = $function;
        return $this;
    }

    function select($columnName, Select $select)
    {
        $this->subSelects[$columnName] = $select;
        return $this;
    }

    function fetch()
    {
        $table   = $this->table;
        $columns = empty($this->columns) ? '*' : implode(', ', $this->columns);
        $where   = empty($this->query) ? '1' : implode(' AND ', $this->query);
        $offset  = $this->limit * ($this->page - 1);
        $limit   = $this->limit;
        $sql     = "SELECT {$columns} FROM {$table} WHERE {$where} LIMIT {$offset},{$limit}";
        $stmt    = $this->pdo->prepare($sql);
        
        // -------------------------------
        
        $stmt->execute($this->queryValues);

        // if ($columns == '*') {
        //     $count = 0;
        //     while($columnMeta = $stmt->getColumnMeta($count++)) {
        //         $this->columns[] = $columnMeta['name'];
        //     }   
        // }
        
        // return $stmt->fetchAll(\PDO::FETCH_ASSOC|\PDO::FETCH_FUNC, function(... $values) use ($stmt) {
        //     return array_combine($this->columns, $values);
        // });

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (!empty($this->forEachFns)) {
            foreach($result as $key => $row) {
                foreach($this->forEachFns as $columnName => $fn) {
                    $result[$key][$columnName] = $fn($row);
                }   
            }
        }

        if (!empty($this->subSelects)) {
            foreach($result as $key => $row) {
                foreach($this->subSelects as $columnName => $select) {
                    $result[$key][$columnName] = $select->fetch();
                }   
            }
        }
        
        return $result;
    }
};