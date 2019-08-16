<?php
// Antes que nada, requerimos el autoload.
require __DIR__ . '/../autoload.php';

//session_start();
use DaVinci\Storage\Session;

Session::start();

// Guardamos la ruta absoluta de base del proyecto.
// Esto va a ser necesario para poder ofrecer métodos que creen URLs
// absolutas para el sitio.
// c:\xampp\htdocs\santiago\mvc\public\..\
// c:\xampp\htdocs\santiago\mvc\
$rootPath = realpath(__DIR__ . '/../');

// Normaliazmos las \ a /
$rootPath = str_replace('\\', '/', $rootPath);

// Requerimos las rutas de la aplicación.
require $rootPath . '/app/routes.php';

// Instanciamos nuestra App.
$app = new \DaVinci\Core\App($rootPath);

// Arrancamos la App.
$app->run();