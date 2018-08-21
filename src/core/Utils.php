<?php
namespace BrummelMW\core;

class Utils
{
    public static function filtrar(array $array_filtrar, string $filtro): array
    {
        return array_filter(array_map(function ($personaje) use ($filtro) {
            $strpos = strpos(mb_strtoupper($personaje), mb_strtoupper($filtro));
            if ($strpos !== false) {
                return str_replace($filtro, BOLD.$filtro.BOLD_CERRAR, $personaje);
            }
            return "";
        }, $array_filtrar));
    }
}