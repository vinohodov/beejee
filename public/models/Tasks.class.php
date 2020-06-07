<?php

namespace models;

use lib\PgSQL;
use lib\Config;

class Tasks {
    
    private $PgSQL;
    
    public function __construct()
    {
        $this->PgSQL = new PgSQL(Config::DB_HOST, Config::DB_PORT, Config::DB, Config::DB_USER, Config::DB_PASSWORD);
    }
    
    public function add($data)
    {
        $query = "INSERT INTO tasks (username, email, description, created_on) VALUES ('" . $data['username'] . "', '" . $data['email'] . "', '" . $data['description'] . "', NOW())";
        $this->PgSQL->sql_add($query);
        return $this->PgSQL->transaction();
    }
    
    public function getAll($data)
    {
        $query = "SELECT * FROM tasks";
        if (isset($data['order'])) {
            $query .= " ORDER BY " . $data['order'];
            if (isset($data['sorting'])) $query .= " " . $data['sorting'];
        }
        if (isset($data['limit'])) {
            $query .= " LIMIT " . $data['limit'];
            if (isset($data['offset'])) $query .= " OFFSET " . $data['offset'];
        }
        return $this->PgSQL->select($query);
    }

    public function getById($id) {
        $query = "SELECT * FROM tasks WHERE id = " . $id;
        return $this->PgSQL->select_simple($query);
    }

    public function count() {
        $query = "SELECT count(*) AS count FROM tasks";
        return ($this->PgSQL->select_simple($query))['count'];
    }
    
    public function commit($id)
    {
        $query = "UPDATE tasks SET status = true WHERE id = " . $id;
        $this->PgSQL->sql_add($query);
        return $this->PgSQL->transaction();
    }

    public function updateDescription($id, $description)
    {
        $query = "UPDATE tasks SET description = '" . $description . "', edited = true WHERE id = " . $id;
        $this->PgSQL->sql_add($query);
        return $this->PgSQL->transaction();
    }
}       