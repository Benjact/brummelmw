<?php
namespace BrummelMW\acciones\swgoh;

use BrummelMW\acciones\AccionBasica;
use BrummelMW\acciones\iAcciones;

class SWGOH implements iSWGOH
{
    public static function recuperarJSON(): array
    {
        return json_decode(file_get_contents("https://swgoh.gg/api/guilds/7217/units/"), true);
    }
}