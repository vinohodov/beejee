<?php

namespace controllers;

use lib\Request;
use lib\Template;
use lib\Data;
use models\TasksMySQL;

class EditController
{
    function index(Request $request)
    {
        $tasks = new TasksMySQL();
        $data['path'] = 'edit';
        if ($request->commit) $tasks->commit($request->commit);
        if ($request->description && 
            $request->id && 
            ($tasks->getById($request->id)['description'] != $request->description)
        ) $data['update_description'] = $tasks->updateDescription($request->id, $request->description);
        if ($request->edit) $data['edit'] = $tasks->getById($request->edit);
        $result = array_merge($data, Data::get($request, $tasks));
        (new Template)->send('tasks', $result);
    }
}