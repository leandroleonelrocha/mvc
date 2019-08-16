<?php

namespace DaVinci\Core;

/**
 * Class Route
 * @package DaVinci\Core
 *
 * Se encarga de manejar todo lo relativo a las rutas.
 *
 * Las rutas las vamos a guardar con la siguiente nomenclatura:

    'GET' => [
        'ruta' => 'NombreController@método'
    ]

    Por ejemplo:

    'GET' => [
        '/peliculas' => 'PeliculaController@index',
        '/peliculas/nueva' => 'PeliculaController@formAlta',
        '/peliculas/{id}' => 'PeliculaController@ver',
        '/peliculas/{id}/eliminar' => 'PeliculaController@ver',
        '/perfil' => 'UsuarioController@perfil',
    ]
 */
class Route
{
    /** @var array Las rutas de todos los verbos. */
    protected static $routes = [
        'GET'       => [],
        'POST'      => [],
        'PUT'       => [],
        'PATCH'     => [],
        'DELETE'    => [],
    ];

    /** @var string  La acción del Controller a ejecutar. Ej: "PeliculaController@index" */
    protected static $controllerAction;

    /** @var array  Los parámetros parseados de la url, cuando esta contiene {}. */
    protected static $urlParameters = [];

    /**
     * Registra una ruta en la aplicación.
     *
     * @param string $method    El verbo HTTP de la ruta. Puede ser 'GET', 'POST', 'PUT', 'DELETE'.
     * @param string $url   La url de la ruta.
     * @param string $controller    El método del controller que lo va a manejar. La notación es: "NombreController@nombreMétodo".
     */
    public static function add($method, $url, $controller)
    {
        $method = strtoupper($method);
        // Ej:
        // self::$routes['GET']['/'] = 'HomeController@index';
        self::$routes[$method][$url] = $controller;
    }

    /**
     * Verifica si la ruta existe.
     *
     * @param string $method
     * @param string $url
     * @return boolean
     */
    public static function exists($method, $url)
    {
        // Para saber si la ruta existe, tengo que contemplar 2 posibles
        // escenarios:
        // 1. La ruta es exacta a como la guardamos
        //  Ej: /productos
        // 2. La ruta es dinámica
        //  Ej: /productos/{id}

        // Verificamos si la ruta exista tal cual me la piden.
        if(isset(self::$routes[$method][$url])) {
            return true;
        }
        // Verificamos si existe una ruta
        // parametrizada (que contenga valores entre
        // {}) que matchee la ruta que nos piden.
        else if(self::parameterizedRouteExists($method, $url)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Indica si existe una ruta parametrizada
     * que matchee la $url para el $method.
     *
     * Adicionalmente, va a parsear y almacenar
     * los datos de la url.
     *
     * @param string $method
     * @param string $url
     * @return bool
     */
    public static function parameterizedRouteExists($method, $url)
    {
        // Para saber si la URL que nos piden matchea con alguna URL
        // que tiene parámetros, tenemos que verificar:
        // 1. Que tengan la misma cantidad de "partes" (valores separados
        //  por '/').
        // 2. Que las partes de la ruta que no son dinámicas (no están
        //  entre llaves '{}') sea exactas entre la ruta y la URL que
        //  me piden.
        // 3. Que las partes que no sean exactas, coincidan con algún
        //  parámetro (entre {}). En el caso de que sea así, entonces
        //  también debemos guardar el valor que corresponde a ese
        //  parámetro.

        // Primero, explotamos la $url.
        $urlParts = explode('/', $url);
        // Ej:
        //  [0] => peliculas
        //  [1] => 1
        //  [2] => eliminar

        // Recorremos todas las rutas para este
        // $method.
        foreach (self::$routes[$method] as $route => $controllerAction) {
            // Definimos una variable que guarde si la ruta matchea o no.
            // Por defecto, asumimos que sí.
            $routeMatches = true;

            // Explotamos la $route.
            $routeParts = explode('/', $route);
            // Ej:
            //  [0] => peliculas
            //  [1] => {id}
            //  [2] => eliminar

            $urlData = [];

            // Verificamos que cuenten con la misma cantidad
            // de partes.
            if(count($routeParts) != count($urlParts)) {
                // Esta ruta no matchea la URL.
                $routeMatches = false;
            } else {
                // Como la ruta y la URL tienen la misma cantidad de
                // partes, entonces las recorremos y comparamos.
                foreach ($routeParts as $key => $part) {
                    // Por cada parte, preguntamos si coincide la URL
                    // con la ruta.
                    if($routeParts[$key] != $urlParts[$key]) {
                        // No son idénticas, así que hay una de dos
                        // posibilidades:
                        // a. Que no coinciden porque la parte de la ruta
                        //  es un parámetro ({}).
                        // b. Que no coinciden porque la ruta no es para
                        //  esta URL.
                        // Verificamos si es un parámetro, si tiene una {
                        if(strpos($routeParts[$key], '{') === 0) {
                            // En efecto esto es un parámetro.
                            // Por lo tanto, debemos ir guardando la
                            // parte de la URL como el parámetro de
                            // la ruta.
                            // Por ejemplo, para poder que el {id} de
                            // la ruta coincide con el 1 de la URL.
                            // Quitamos las llaves de la parte de la ruta
                            // para así obtener el nombre.
                            $parameterName = substr($routeParts[$key], 1, -1);

                            // Guardamos el valor en el array de
                            // $urlData.
                            $urlData[$parameterName] = $urlParts[$key];
                        } else {
                            // No era un parámetro esta parte, ni tampoco
                            // coincide con la URL, así que la ruta no
                            // matchea :(
                            $routeMatches = false;
                        }
                    }
                }
            }

            // Después de hacer el condicional, verificamos si la ruta
            // matchea.
            if($routeMatches) {
                // Genial, encontramos la ruta para la URL!
                // Guardamos los datos de la ruta.
                self::$controllerAction = $controllerAction;
                self::$urlParameters = $urlData;

                // Informamos que la encontramos.
                return true;
            }
        }

        // Si el bucle termina y no me fui por el true, entonces la URL
        // no matchea con ninguna ruta.
        return false;
    }

    /**
     * Retorna el controller asociado a la ruta.
     * Ej: HomeController@index
     *
     * @param string $method
     * @param string $url
     * @return string
     */
    public static function getController($method, $url)
    {
        // Si obtuvimos una url parametrizada,
        // la retornamos.
        if(!is_null(self::$controllerAction)) {
            return self::$controllerAction;
        }

        return self::$routes[$method][$url];
    }

    /**
     * @return array
     */
    public static function getUrlParameters()
    {
        return self::$urlParameters;
    }
}