<?php

require_once '../vendor/autoload.php';

spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    require $class . '.class.php';
});

use lib\Auth;
use lib\Router;
use lib\Request;

session_start();

$request = new Request();
if($request->login && $request->password) Auth::login($request->login, $request->password);
if($request->logout == 1) Auth::logout();

$routes = array(
    'index' => 'controllers\MainController@index',
    'login' => 'controllers\AuthController@index',
    'logout' => 'controllers\AuthController@logout',
    'edit' => ['controllers\EditController@index', 'auth']
);

(new Router($request))->midleware($routes);
