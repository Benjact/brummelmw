<?php
namespace BrummelMW\acciones\origenSwgoh;

use BrummelMW\acciones\ExcepcionRuta;
use BrummelMW\core\Utils;

class SwgohCharacters implements iSWGOH
{
    public static function recuperarJSON(): array
    {
        $ruta = "https://swgoh.gg/api/characters/?format=json";
        if (!Utils::comprobarExisteRuta($ruta)) {
            throw new ExcepcionRuta($ruta);
        }

        return json_decode(file_get_contents($ruta), true);
    }
}