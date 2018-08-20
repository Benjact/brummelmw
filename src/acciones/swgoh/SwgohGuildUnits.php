<?php
namespace BrummelMW\acciones\swgoh;

use BrummelMW\acciones\AccionBasica;
use BrummelMW\acciones\iAcciones;

class SwgohGuildUnits implements iSWGOH
{
    public static function recuperarJSON(): array
    {
        return json_decode(file_get_contents("https://swgoh.gg/api/guilds/".ID_GREMIO."/units/"), true);
    }
}