<?php

namespace DaVinci\Core;

/**
 * Class App
 * @package DaVinci\Core
 *
 * Maneja el funcionamiento básico de la aplicación.
 */
class App
{
    // Definimos unas propiedades rutas del sitio:
    /** @var string Ruta de la raíz. Apunta al directorio. Para uso interno. */
    private static $rootPath;
    /** @var string Ruta de la app. Apunta al directorio. Para uso interno. */
    private static $appPath;
    /** @var string Ruta de la carpeta public. Apunta al directorio. Para uso interno. */
    private static $publicPath;
    /** @var string Ruta de la carpeta de vistas. Apunta al directorio. Para uso interno. */
    private static $viewsPath;
    /** @var string La ruta de la página. Apunta a la URL. Para uso en la página. */
    private static $urlPath;

    /** @var Request La petición del usuario. */
    protected $request;

    /**
     * App constructor.
     * @param $rootPath
     */
    public function __construct($rootPath)
    {
        // En el constructor, calculamos todas las rutas que necesitamos.
        // Empezamos por los directorios.
        self::$rootPath = $rootPath;
        self::$appPath = $rootPath . '/app';
        self::$publicPath = $rootPath . '/public';
        self::$viewsPath = $rootPath . '/views';

        // Calculamos la URL.
        self::$urlPath = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] .  $_SERVER['SCRIPT_NAME'];

        // Le restamos el "index.php" la final de la URL, para generar
        // la URL limpia.
        self::$urlPath = substr(self::$urlPath, 0, -9);
    }

    /**
     * Arranca la aplicación.
     */
    public function run()
    {
        // Obtenemos la petición.
        $this->request = new Request();

        // Verificamos si la ruta existe.
        if(Route::exists($this->request->getMethod(), $this->request->getRequestedUrl())) {
            // Si la ruta existe, entonces le pedimos a Route que
            // nos retorne el controller para esa ruta.
            $controller = Route::getController($this->request->getMethod(), $this->request->getRequestedUrl());
            // Ejecutamos el método del controller asociado.
            $this->executeController($controller);
        } else {
            throw new \Exception("No existe la ruta especificada.");
            // Opcionalmente, podemos directamente llamar a una página que muestre un error 404 o una página
            // template que diga que el recurso no se encontró.
        }
    }

    /**
     * Instancia el controller y ejecuta el método.
     *
     * @param string $controller El controller y su método.
     */
    public function executeController($controller)
    {
        // $controller = "HomeController@index";
        $controllerData = explode('@', $controller);
        $controllerName = $controllerData[0];
        $controllerMethod = $controllerData[1];

        // $controllerName = "HomeController";
        // Le agregamos el namespace a la clase.
        $controllerName = "\\DaVinci\\Controllers\\" . $controllerName;
        // Esto nos deja, ej:
        // \DaVinci\Controllers\HomeController

        // Instanciamos el controller.
        // Ej: new \DaVinci\Controllers\HomeController
        $controllerObject = new $controllerName;

        // Ejecutamos su método.
        $controllerObject->{$controllerMethod}();
    }

    /**
     * Redirecciona al $path indicado.
     *
     * @param string $path
     */
    public static function redirect($path = '')
    {
        header('Location: ' . self::urlTo($path));
        exit;
    }

    /**
     * Retorna una url absoluta para el $path indicado.
     *
     * @param string $path
     * @return string
     */
    public static function urlTo($path = '')
    {
        // Quitamos la barra de inicio de la ruta, de estar presente.
        if(strpos($path, '/') === 0) {
            $path = substr($path, 1);
        }

        return self::$urlPath . $path;
    }

    /**
     * @return mixed
     */
    public static function getRootPath()
    {
        return self::$rootPath;
    }

    /**
     * @return string
     */
    public static function getAppPath()
    {
        return self::$appPath;
    }

    /**
     * @return string
     */
    public static function getPublicPath()
    {
        return self::$publicPath;
    }

    /**
     * @return string
     */
    public static function getViewsPath()
    {
        return self::$viewsPath;
    }

    /**
     * @return mixed
     */
    public static function getUrlPath()
    {
        return self::$urlPath;
    }
}