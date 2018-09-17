<?php
namespace BrummelMW\acciones\origenSwgoh;

use BrummelMW\acciones\ExcepcionRuta;

class SwgohGuildUnits implements iSWGOH
{
    public static function recuperarJSON(): array
    {
        $ruta = "https://swgoh.gg/api/guild/" . ID_GREMIO . "/";
        if (file_exists($ruta)) {
            $array_retorno = json_decode(file_get_contents($ruta), true);
        } else {
            throw new ExcepcionRuta($ruta);
        }
        return $array_retorno;
    }
}