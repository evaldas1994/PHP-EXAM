<?php

namespace Core;

class Db
{
    public const USER_TABLE = 'user';
    public const MAP_FIELD_TABLE = 'map_field';
    public const FIELD_TYPE_TABLE = 'field_type';

    private $host = DB_HOST;
    private $dbuser = DB_USER;
    private $dbname = DB_NAME;
    private $dbpass = DB_PASSWORD;
    private $pdo;

    public function __construct()
    {
        $dsn = 'mysql:dbname=' . $this->dbname . ';host=' . $this->host;

        try {
            $this->pdo = new \PDO($dsn, $this->dbuser, $this->dbpass);
        } catch (\PDOException $e) {
            echo $e;
        }
    }

    private $sql;

    // get plain SQL query
    public function getSql()
    {
        return $this->sql;
    }

    // Execute query
    public function exec()
    {
        if ($this->pdo->query($this->sql)) {
            $lastId = $this->pdo->lastInsertId();
            return $lastId;
        } else {
            echo "Error: " . $this->sql . "<br>";
            print_r($this->pdo->errorInfo());
        }
    }

    // Get Results
    public function get(): array
    {
        $statment = $this->pdo->query($this->sql);
        $rez = [];
        while ($row = $statment->fetch()) {
            $rez[] = $row;
        }

        return $rez;
    }

    public function getOne()
    {
        $statment = $this->pdo->query($this->sql);
        $rez = [];
        while ($row = $statment->fetch()) {
            $rez[] = $row;
        }

        return isset($rez[0]) ? $rez[0] : null;
    }


    public function select($fields = '*')
    {
        $this->sql = 'SELECT ' . $fields . ' ';
        return $this;
    }

    public function from($table)
    {
        $this->sql .= 'FROM ' . $table . ' ';
        return $this;
    }

    public function where($field, $value)
    {
        $this->sql .= 'WHERE ' . $field . '="' . $value . '"';
//        $this->sql.="WHERE $field = '$value'";
        return $this;
    }

    public function delete()
    {
        $this->sql = 'DELETE ';
        return $this;
    }

    public function insert($table)
    {
        $this->sql = 'INSERT INTO ' . $table . ' ';
        return $this;
    }

    public function values($values)
    {
        $this->sql .= '(';
        $valueLine = '';
        foreach ($values as $columnName => $value) {
            if ($value !== 'null') {
                $this->sql .= $columnName . ', ';
                $valueLine .= '"' . $value . '"' . ', ';
            }
        }
        $this->sql = rtrim($this->sql, ', ') . ' ';
        $this->sql .= ') ';
        $this->sql .= 'VALUES (' . rtrim($valueLine, ', ') . ')';
        return $this;
    }

    public function update($table)
    {
        $this->sql = 'UPDATE ' . $table . ' ';
        return $this;
    }

    public function set($values)
    {
        $this->sql .= 'SET ';
        foreach ($values as $columnName => $value) {
            $this->sql .= $columnName . '="' . $value . '", ';
        }
        $this->sql = rtrim($this->sql, ', ') . ' ';
        return $this;
    }

    public function whereAnd($field, $value)
    {
        $this->sql .= ' AND ' . $field . '="' . $value . '"';
        return $this;
    }

    public function truncate($table)
    {
        $this->sql = 'TRUNCATE '.$table;
        return $this;
    }

    public function left($table)
    {
        $this->sql .= 'LEFT JOIN '.$table.' ';
        return $this;
    }

    public function on($table1, $column1, $table2, $column2)
    {
        $this->sql .= 'ON '.$table1.'.'.$column1 .' = '.$table2.'.'.$column2.' ';
        return $this;
    }

    public function getAllMessagesOfRoom($user_id, $foreign_id): Db
    {
        $this->sql = 'select * from chat where (from_id = ' . $user_id . ' or from_id = ' . $foreign_id . ') and (to_id = ' . $user_id . ' or to_id = ' . $foreign_id . ');';
        return $this;
    }

    public function getAllUnseenMessagesOfRoom($user_id, $foreign_id): Db
    {
        $this->sql = 'select * from chat where (from_id = ' . $foreign_id . ') and (to_id = ' . $user_id . ') and (is_seen = 0);';
        return $this;
    }

    public function getAllRooms($user_id): Db
    {
        $this->sql = 'select distinct from_id, to_id from chat where from_id = ' . $user_id . ' or to_id = ' . $user_id . '  order by to_id;';
        return $this;
    }

    public function getLastMessageByUsers($user_id, $foreign_id): Db
    {
        $this->sql = 'select * from chat where (from_id = ' . $user_id . ' and to_id = ' . $foreign_id . ') or (from_id = ' . $foreign_id . ' and to_id = ' . $user_id . ') order by id desc limit 1;';
        return $this;
    }

}