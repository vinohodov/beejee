<?php

namespace controllers;

use lib\Request;
use lib\Template;

class AuthController
{
    function index(Request $request)
    {
        (new Template)->send('login');
    }
    
    function logout(Request $request)
    {
        (new Template)->send('tasks');
    }
}