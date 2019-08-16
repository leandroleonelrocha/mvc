<?php
/*
 * Este archivo va a contener TODAS las rutas de
 * nuestra aplicación.
 *
 * Cada ruta básicamente significa cada URL que el
 * usuario puede visitar.
 *
 * Para esto, vamos a crear una clase Route cuya
 * función sea la de registrar y administrar las rutas.
 */
use DaVinci\Core\Route;

// Registramos la primer ruta! :D
// Recibe 3 parámetros:
// 1. String. El verbo (GET, POST, PATCH, PUT, DELETE).
// 2. String. La URL a partir de la carpeta public.
// 3. String. El método y controller que va a manejar
//      esta petición.
//      La sintaxis es: NombreController@nombreMétodo

// Por ejemplo, esta ruta está diciendo que
// Cuando el usuario ingrese a la raíz de public vía
// GET, ejecutá el método "index" de la clase 
// HomeController.
Route::add('GET', '/', 'HomeController@index');

Route::add('GET', '/quienes-somos', 'HomeController@quienesSomos');

Route::add('GET', '/login', 'AuthController@loginForm');
Route::add('POST', '/login', 'AuthController@doLogin');
Route::add('GET', '/logout', 'AuthController@doLogout');

Route::add('GET', '/productos', 'ProductosController@listado');
Route::add('GET', '/productos/crear', 'ProductosController@crearForm');
Route::add('POST', '/productos/crear', 'ProductosController@grabar');
// Las llaves indican que es un valor que puede variar.
// El nombre del parámetro (el texto dentro de las llaves) es arbitrario,
// y define el nombre con que el vamos a poder pedir ese valor a la
// ruta.
Route::add('GET', '/productos/{id}', 'ProductosController@verDetalle');

// Coming Soon.
Route::add('POST', '/productos/{id}/eliminar', 'ProductosController@eliminar');






