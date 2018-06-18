<?php
namespace Page;

class Login
{
    public static $URL = '/#auth/login';
    public static $loginField = "input[name=username]";
    public static $passwordField = "input[name=pass]";
    public static $loginButton = "Вход";

    public static function getLogin($name)
    {
        //$name = 'Ололоша';
        $name = 'admin';
        return $name;
    }

    public static function getPassword($name)
    {
        //$pwd = '123456789';
        $pwd = 'kahkahjoh1';
        return $pwd;
    }

}
