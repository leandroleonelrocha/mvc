<?php

namespace DaVinci\Core;

class View
{
    protected static $mainLayout = "layout/main";

    /**
     * Imprime la vista indicada.
     *
     * @param string $__vista   La ruta de la vista, sin el php.
     * @param array $__data     Los datos a proporcionale a la vista. El índice va a ser el nombre de la variable, y el valor, el dato asignado.
     */
    public static function render($__vista, $__data = [])
    {
        // Ej:
        // $__data = ['peliculas' => $peliculas];
//        $peliculas = $__data['peliculas'];

        // Cargamos los datos para la vista.
        // Recorremos el array de $__data, y por cada item que tenga
        // el array, creamos una variable en este ámbito de la función.
        foreach ($__data as $key => $value) {
            // $key = 'productos';
            // ${$key} => $productos
            ${$key} = $value;
        }

        // Incluimos el header.
        require App::getViewsPath() . '/templates/header.php';

        require App::getViewsPath() . '/' . $__vista . ".php";

        // Incluimos el footer.
        require App::getViewsPath() . '/templates/footer.php';
    }

    /**
     * Renderiza la vista usando el layout definido.
     *
     * @param string $__vista
     * @param array $__data
     * @param null|string $layout
     */
    public static function renderWithLayout($__vista, $__data = [], $layout = null)
    {
        $layout = $layout ?? self::$mainLayout;

        // Iniciamos el output_buffering, para capturar todas las salidas,
        // _sin_ que se envíen al cliente.
        ob_start();
        // Cargamos los datos para la vista.
        foreach ($__data as $key => $value) {
            ${$key} = $value;
        }
        $output = "";

        require App::getViewsPath() . "/" . $layout . ".php";

        // Le pedimos a ob que nos de los contenidos que se fueron acumulando
        // hasta ahora (básicamente, el layout) como un string.
        $__content__ = ob_get_contents();
        // Vaciamos el buffer.
        ob_clean();

        require App::getViewsPath() . "/" . $__vista . ".php";

        // Le pedimos a ob que nos de los nuevos contenidos que se acumularon
        // después de que vacíamos el buffer.
        $__view__ = ob_get_contents();

        // Vacíamos el buffer y apagamos el ob.
        ob_end_clean();

        $__content__ = str_replace("@{{content}}", $__view__, $__content__);

        echo $__content__;
    }

    /**
     * Retorna la $data con formato JSON.
     *
     * @param mixed $data
     */
    public static function renderJson($data)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }
}