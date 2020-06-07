<?php

namespace lib;

use controllers\MainController;
use lib\Request;

class Router
{
    public $path;
    private $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->path = $request->path ? $request->path : 'index';
    }
    
    public function midleware(array $routes)
    {
        $route = isset($routes[$this->path]) ? $routes[$this->path] : 'controllers\NoRouteController@index';
        if (is_array($route)) {
            if (($route[1] == 'auth') && Auth::state()) {
                $current_route = $route[0];
            } else {
                $current_route = $routes['login'];
            }
        } else {
            $current_route = $route;
        }
        list($controller, $method) = explode('@', $current_route);
        
        (new $controller())->$method($this->request);
    }
}