<?php

namespace lib;

use lib\Auth;
use lib\Link;
use models\Tasks;

class Data 
{
    public static function get(Request $request, Tasks $tasks) {
        $link = new Link($request);
        if (Auth::state()) $data['auth'] = true;
        $dataSQL['limit'] = 3;
        if ($request->page) $dataSQL['offset'] = ($request->page - 1) * $dataSQL['limit'];
        if ($request->order) { 
            $dataSQL['order'] = $request->order;
            if ($request->sorting) $dataSQL['sorting'] = $request->sorting;
        }
        $data['list_tasks'] = $tasks->getAll($dataSQL);
        $data['count_tasks'] = $tasks->count();
        if ($data['count_tasks'] > $dataSQL['limit']) {
            $data['pagination']['count_pages'] = ceil($data['count_tasks'] / $dataSQL['limit']);
            $data['pagination']['current_page'] = $request->page ? $request->page : 1;
            $data['pagination']['prev_page'] = $data['pagination']['current_page'] > 1 ? $data['pagination']['current_page'] - 1 : 0;
            $data['pagination']['next_page'] = $data['pagination']['current_page'] < $data['pagination']['count_pages'] ? $data['pagination']['current_page'] + 1 : 0;
            $data['pagination']['link'] = $link->pagination();
        }
        $data['links']['username'] = $link->columnOrder('username');
        $data['links']['email'] = $link->columnOrder('email');
        $data['links']['status'] = $link->columnOrder('status');
        $data['links']['common'] = $link->common();
        return $data;
    }
}