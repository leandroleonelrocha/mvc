<?php


namespace Application\Controllers;


use Application\Auth\Auth;
use Application\Core\App;
use Application\Storage\Session;

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