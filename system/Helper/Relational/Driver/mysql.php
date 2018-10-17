<?php

namespace Helper\Relational\Driver;

use Helper\Relational\Map;

class mysql
{
    const sqlGetDatabaseName = "SELECT DATABASE()";
    const sqlGetTables       = "SHOW TABLES";
    const sqlSelect          = "SELECT {columns} FROM {table} WHERE {where} LIMIT {limit},{offset}";
    const sqlInsert          = "INSERT INTO {table}({columns}) VALUES({values})";
    const sqlUpdate          = "UPDATE {table} SET {set} WHERE {where}";
    const sqlDelete          = "DELETE FROM {table} WHERE {where}";

    protected $databaseName = null;
    protected $tables = null;
    
    function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    function fetch(string $query, Array $values = [], $type = \PDO::FETCH_OBJ) : Array
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
        return $stmt->fetchAll($type);
    }

    function fetchNum(string $query, Array $values = []) : Array
    {
        return $this->fetch($query, $values, \PDO::FETCH_NUM);
    }

    function fetchCol(string $query, Array $values = []) : Array
    {
        return $this->fetch($query, $values, \PDO::FETCH_COLUMN);
    }

    function execute(string $query, Array $values) : \PDOStatement
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
        return $stmt;
    }

    function getDatabaseName() : string
    {
        return $this->databaseName
            ?? $this->databaseName = $this->fetchNum(Self::sqlGetDatabaseName)[0][0];
    }

    function getTables() : Array
    {
        return $this->tables 
            ?? $this->tables = array_fill_keys($this->fetchCol(Self::sqlGetTables), null);
    }

    function tableExists(string $table, bool $throw = false) : bool
    {
        $check = array_key_exists($table, $this->getTables()); 
        if ($throw && !$check)
            throw new \Exception("Table `$table` does not exists in `". $this->getDatabaseName() ."` database. <--- melhorar");
        return $check;
    }
    
    function columnExists(string $table, bool $throw = false, string $column = null, string ... $columns)
    {
        $this->tableExists($table, true);
        if (!$column)
            return true;
        $checkColumns = array_merge((Array)$column, $columns);
        $existentColumns = array_keys($this->describeTable($table)->columns);
        $notFound = array_diff($checkColumns, $existentColumns);
        if ($throw && !empty($notFound))
            throw new \Exception("Table `$table` does not contain the following fields `". implode('`, `', $notFound) ."` database. <--- melhorar");
        return empty($notFound);
    }

    function describeTable(string $table) : Object
    {
        $this->tableExists($table, true);

        if ($description = $this->tables[$table])
            return $description;
            
        $query = "DESCRIBE {$table}";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $columns = $stmt->fetchAll(\PDO::FETCH_OBJ);
        $result = (Object)[
            'columns' => null,
            'primarys' => null
        ];

        foreach($columns as $column)
        {
            $result->columns[ $column->Field ] = [
                "null" => $column->Null
            ];

            if ($column->Key == "PRI") 
                $result->primarys[] = $column->Field;
        }

        return $this->tables[$table] = $result;
    }
    
    function select(string $table, Array $columns, Map\Condition $where, int $limit = 0, int $offset = 1000) : Array
    {
        $this->columnExists($table, true, ... $columns);

        $condMeta = $where->mount(); 
        
        $replace = [
            "{table}" => $table,
            "{columns}" => count($columns) ? implode(', ', $columns) : "*",
            "{where}" => $condMeta->query,
            "{limit}" => $limit,
            "{offset}" => $offset 
        ];
        
        $query = str_replace(
            array_keys($replace),
            array_values($replace),
            Self::sqlSelect
        );
        
        return $this->fetch($query, $condMeta->values);
    }
    
    function insert(string $table, Map\Values $values) : int
    {
        $this->tableExists($table, true);
        
        $condMeta = $values->mountSimple();
        
        $replace = [
            "{table}" => $table,
            "{columns}" => implode(', ', $values->columns()),
            "{values}" => ":". implode(', :', array_keys($condMeta))
        ];
        
        $query = str_replace(
            array_keys($replace),
            array_values($replace),
            Self::sqlInsert
        );
        
        return $this->execute($query, $condMeta)->rowCount();
    }

    function update(string $table, Map\Values $values, Map\Condition $where) : int
    {
        $this->tableExists($table, true);
        
        $condMeta = $where->mount();
        $sets = [];
        $vals = $condMeta->values;

        foreach($values->mount() as $key => $part) {
            $sets[] = "{$part['column']}=:{$key}";
            $vals[$key] = $part['value'];
        }
        
        $replace = [
            "{table}" => $table,
            "{set}"  => implode(', ', $sets),
            "{where}" => $condMeta->query,
        ];
        
        $query = str_replace(
            array_keys($replace),
            array_values($replace),
            Self::sqlUpdate
        );
        
        return $this->execute($query, $vals)->rowCount();
    }
    
    function save(string $table, Map\Values $values, Map\Condition $where = null) : int
    {
        return $where
            ? $this->update($table, $values, $where)
            : $this->insert($table, $values);
    }

    function delete(string $table, Map\Condition $where) : int
    {

        $this->tableExists($table, true);
        
        $condMeta = $where->mount(); 
        
        $replace = [
            "{table}" => $table,
            "{where}" => $condMeta->query
        ];
        
        $query = str_replace(
            array_keys($replace),
            array_values($replace),
            Self::sqlDelete
        );

        return $this->execute($query, $condMeta->values)->rowCount();
    }
}