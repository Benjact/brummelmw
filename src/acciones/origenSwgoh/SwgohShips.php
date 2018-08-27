<?php
namespace BrummelMW\acciones\origenSwgoh;

use BrummelMW\acciones\AccionBasica;
use BrummelMW\acciones\iAcciones;

class SwgohShips implements iSWGOH
{
    public static function recuperarJSON(): array
    {
        return json_decode(file_get_contents("https://swgoh.gg/api/ships/?format=json"), true);
    }
}