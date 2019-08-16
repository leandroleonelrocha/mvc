<?php

namespace DaVinci\Storage;

class Session
{
    /**
     * Inicia la sesión.
     */
    public static function start()
    {
        session_start();
    }

    /**
     * Destruye la sesión.
     */
    public static function destroy()
    {
        session_destroy();
    }

    /**
     * Obtiene el $item en la sesión. Si existe, lo elimina además de
     * retornarlo.
     *
     * @param string $item
     * @return mixed
     */
    public static function flash($item)
    {
        $valor = $_SESSION[$item] ?? null;
        self::delete($item);
        return $valor;
    }

    /**
     * Obtiene el $item en la sesión.
     * Si no existe, retorna null.
     *
     * @param string $item
     * @return mixed
     */
    public static function get($item)
    {
        return $_SESSION[$item] ?? null;
    }

    /**
     * Almacena el $item en la sesión.
     *
     * @param string $item
     * @param mixed $value
     */
    public static function set($item, $value)
    {
        $_SESSION[$item] = $value;
    }

    /**
     * Elimina el $item de la sesión.
     *
     * @param string $item
     */
    public static function delete($item)
    {
        unset($_SESSION[$item]);
    }

    /**
     * Informa si existe el $item en la sesión.
     *
     * @param string $item
     * @return bool
     */
    public static function has($item)
    {
        return isset($_SESSION[$item]);
    }
}