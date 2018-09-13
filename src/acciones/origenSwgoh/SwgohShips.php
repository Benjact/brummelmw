<?php
namespace BrummelMW\acciones\origenSwgoh;

use BrummelMW\acciones\AccionBasica;
use BrummelMW\acciones\iAcciones;

class SwgohShips implements iSWGOH
{
    public static function recuperarJSON(): array
    {
        $ruta = "https://swgoh.gg/api/ships/?format=json";
        $array_retorno = json_decode(file_get_contents($ruta), true);
        if ($array_retorno == null) {
            throw new ExcepcionRuta($ruta);
        }
        return $array_retorno;
    }
}