<?php
namespace BrummelMW\acciones\origenSwgoh;

use BrummelMW\acciones\AccionBasica;
use BrummelMW\acciones\ExcepcionAccion;
use BrummelMW\acciones\ExcepcionRuta;
use BrummelMW\acciones\iAcciones;

class SwgohGuildUnits implements iSWGOH
{
    public static function recuperarJSON(): array
    {
        $ruta = "https://swgoh.gg/api/guilds/" . ID_GREMIO . "/units/";
        $array_retorno = json_decode(file_get_contents($ruta), true);
        if ($array_retorno == null) {
            throw new ExcepcionRuta($ruta);
        }
        return $array_retorno;
    }
}