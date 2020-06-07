<?php

namespace controllers;

use lib\Request;
use lib\Template;
use lib\Data;
use models\Tasks;

class MainController
{
    
    function index(Request $request)
    {
        $tasks = new Tasks();
        $data['path'] = '';
        if ($request->username && $request->email && $request->description) {
            $data['result_adding'] = $tasks->add(array('username' => $request->username, 'email' => $request->email, 'description' => $request->description));
        }
        $result = array_merge($data, Data::get($request, $tasks));
        (new Template)->send('tasks', $result);
    }
}