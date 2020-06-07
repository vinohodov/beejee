<?php

namespace lib;

class Store
{
    public static function setState($name, $value)
    {
        return $_SESSION[$name] = $value;
    }
    
    public static function getState($name)
    {
        return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
    }
}