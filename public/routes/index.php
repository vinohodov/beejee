<?php

$routes = array(
    'index' => 'MainController@index',
    'login' => 'AuthController@index',
    'logout' => 'AuthController@logout',
    'edit' => ['EditController@index', 'auth']
);

