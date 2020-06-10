<?php

namespace controllers;

use lib\Request;
use lib\Template;
use lib\Data;
use models\TasksMySQL;

class MainController
{
    
    function index(Request $request)
    {
        $tasks = new TasksMySQL();
        $data['path'] = '';
        if ($request->username && $request->email && $request->description) {
            $data['result_adding'] = $tasks->add(array('username' => $request->username, 'email' => $request->email, 'description' => $request->description));
        }
        $result = array_merge($data, Data::get($request, $tasks));
        (new Template)->send('tasks', $result);
    }
}