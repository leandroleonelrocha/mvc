<?php
namespace DaVinci\DB;

use PDO;

class Connection
{
    // La propiedad estática pertenece a la clase, y
    // por ende, existe mientras la clase exista.
    private static $db = null;
    
    /**
     * Constructor privado para evitar que se instancie
     * la clase.
     */
    private function __construct()
    {}
    
    /**
     * Esta función retorna la conexión a la base de 
     * datos en PDO.
     *
     * Recuerden que al ser static, debe llamarse desde
     * la propia clase.
     *
     */
    public static function getConnection()
    {
//        echo "Obteniendo conexión :D";
        // Haciendo
        //      Clase::$variable
        // es como accedemos a las propiedades estáticas.
        if(self::$db === null) {
            // Definimos los parámetros de la conexión.
            $db_host = "localhost";
            $db_user = "root";
            $db_pass = "";
            $db_base = "prog3";
            $db_charset = "utf8mb4"; // Este es el UTF-8 posta

            $db_dsn = "mysql:host={$db_host};dbname={$db_base};charset={$db_charset}";

            // Guardamos la conexión en la propiedad
            // estática de la clase.
            self::$db = new PDO($db_dsn, $db_user, $db_pass);
//            echo "Conexión abierta :D";
        }
        return self::$db;
    }
}





