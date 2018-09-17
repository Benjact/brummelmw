<?php
namespace BrummelMW\acciones\origenSwgoh;

use BrummelMW\acciones\ExcepcionRuta;
use BrummelMW\core\Utils;

class SwgohGuildUnits implements iSWGOH
{
    public static function recuperarJSON(): array
    {
        $ruta = "https://swgoh.gg/api/guild/" . ID_GREMIO . "/";
        if (!Utils::comprobarExisteRuta($ruta)) {
            throw new ExcepcionRuta($ruta);
        }

        return json_decode(file_get_contents($ruta), true);
    }
}