<?php
namespace BrummelMW\core;

class Utils
{
    public static function filtrar(array $array_filtrar, string $filtro): array
    {
        return array_values(array_filter(array_map(function ($personaje) use ($filtro) {
            $strpos = strpos(mb_strtoupper($personaje), mb_strtoupper($filtro));
            if ($strpos !== false) {
                return str_replace($filtro, BOLD.$filtro.BOLD_CERRAR, $personaje);
            }
            return "";
        }, $array_filtrar)));
    }

    public static function comprobarExisteRuta(string $ruta): bool
    {
        $file_headers = @get_headers($ruta);
        if ($file_headers[0] == 'HTTP/1.0 404 Not Found') {
            return false;
        }

        if ($file_headers[0] == 'HTTP/1.0 302 Found' && $file_headers[7] == 'HTTP/1.0 404 Not Found') {
            return false;
        }
        return true;
    }
}