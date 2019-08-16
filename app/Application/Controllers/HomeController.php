<?php

namespace Application\Controllers;

use Application\Auth\Auth;
use Application\Core\App;
use Application\Core\View;

class HomeController extends BaseController
{
    public function index()
    {
        // Le decimos que imprima la vista "home".
        // Todas las vistas toman como ruta de base
        // la carpeta views.
        // No llevan el ".php" del final
        View::render('home');
    }
    
    public function quienesSomos()
    {
        View::render('quienes-somos');
    }
}