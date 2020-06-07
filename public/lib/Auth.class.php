<?php

namespace lib;

class Auth
{      
    public static function login(string $login, string $password):bool
    {
        if (strtolower($login) == strtolower(Config::LOGIN) && password_verify($password, Config::HASH_PASSWORD)) {
            return Store::setState('Auth', true);
        } else {
            Auth::logout();
            return false;
        }
    }

    public static function logout():bool
    {
        return Store::setState('Auth', false);
    }

    public static function state():?bool
    {
        return Store::getState('Auth');
    }        
}