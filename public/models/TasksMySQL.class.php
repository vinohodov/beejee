<?php

namespace models;

use MysqliDb;
use lib\Config;
use Exception;

class TasksMySQL {
    
    private $db;
    
    public function __construct()
    {
        $this->db = new MysqliDb (Config::DB_HOST, Config::DB_USER, Config::DB_PASSWORD, Config::DB);
    }
    
    public function add($data)
    {
        $data['created_on'] = date('Y-m-d H:i:s', time());
        return $this->db->insert ('tasks', $data);
    }
    
    public function getAll($data)
    {
        $page = $data['page'] ? $data['page'] : 1;
        if ($data['limit']) $this->db->pageLimit = $data['limit'];
        if (isset($data['order'])) {
            if (empty($data['sorting'])) $data['sorting'] = 'asc';
            $this->db->orderBy($data['order'], $data['sorting']);
        }
        $tasks = $this->db->arraybuilder()->paginate('tasks', $page);
        return $tasks;
    }

    public function getById($id) {
        $this->db->where ("id", $id);
        return $this->db->getOne ("tasks");
    }

    public function count() {
        return $this->db->getValue ("tasks", "count(*)");
    }
    
    public function commit($id)
    {
        $data = Array ('status' => 1);
        $this->db->where ('id', $id);
        return $this->db->update ('tasks', $data);
    }

    public function updateDescription($id, $description)
    {
        $data = Array ('description' => $description, 'edited' => 1);
        $this->db->where ('id', $id);
        return $this->db->update ('tasks', $data);
    }
}