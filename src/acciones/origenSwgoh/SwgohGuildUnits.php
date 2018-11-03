<?php
namespace BrummelMW\acciones\origenSwgoh;

use BrummelMW\acciones\ExcepcionRuta;
use BrummelMW\core\Utils;

class SwgohGuildUnits implements iSWGOH
{


    public static function recuperarJSON(): array
    {
        ini_set('memory_limit','256MB');

        $ruta = "https://swgoh.gg/api/guild/" . ID_GREMIO . "/";
        echo $ruta;
        if (!Utils::comprobarExisteRuta($ruta)) {
            throw new ExcepcionRuta($ruta);
        }

        echo file_get_contents($ruta);
        return json_decode(file_get_contents($ruta), true);
    }
}