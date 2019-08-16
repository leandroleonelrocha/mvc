<?php


namespace DaVinci\Controllers;


use DaVinci\Auth\Auth;
use DaVinci\Core\App;
use DaVinci\Storage\Session;

class BaseController
{
    protected function requiresAuth()
    {
        if (!Auth::isLogged()) {
//            $_SESSION['error'] = "Debe iniciar sesión para ver esta pantalla.";
            Session::set('error', "Debe iniciar sesión para ver esta pantalla.");
            App::redirect('login');
        }
    }
}