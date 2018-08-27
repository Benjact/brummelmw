<?php
namespace BrummelMW\acciones\origenSwgoh;

use BrummelMW\acciones\AccionBasica;
use BrummelMW\acciones\iAcciones;

class SwgohCharacters implements iSWGOH
{
    public static function recuperarJSON(): array
    {
        return json_decode(file_get_contents("https://swgoh.gg/api/characters/"), true);
    }
}