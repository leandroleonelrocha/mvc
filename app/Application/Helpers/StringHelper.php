<?php

namespace Application\Helpers;

class StringHelper
{
    /**
     * @param string $string
     * @return mixed
     */
    public static function slug($string)
    {
        // TV LCD 26" MODELO 26LG30R
        // HOME CINEMA HTS3011/55
        // 1. Pasar el texto a minúsculas
        $slug = strtolower($string);
        // 2. Eliminamos los caracteres.
        $slug = str_replace([
            ',',
            '.',
            '+',
            '/',
            '"',
        ], '', $slug);
        // 3. Reemplazar los espacios con "-".
        $slug = str_replace(' ', '-', $slug);

        return $slug;
    }
}