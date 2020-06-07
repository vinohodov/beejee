<?php

namespace controllers;

use lib\Request;
use lib\Template;

class NoRouteController
{
    function index(Request $request)
    {
        
        echo '404. Нет такого роутера';
    }
}