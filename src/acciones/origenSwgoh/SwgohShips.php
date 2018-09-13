<?php
namespace BrummelMW\acciones\origenSwgoh;

use BrummelMW\acciones\ExcepcionRuta;

class SwgohShips implements iSWGOH
{
    public static function recuperarJSON(): array
    {
        $ruta = "https://swgoh.gg/api/ships/?format=json";
        if (file_exists($ruta)) {
            $array_retorno = json_decode(file_get_contents($ruta), true);
        } else {
            throw new ExcepcionRuta($ruta);
        }
        return $array_retorno;
    }
}