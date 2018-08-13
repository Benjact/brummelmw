<?php
namespace BrummelMW\acciones\swgoh;

use BrummelMW\acciones\AccionBasica;
use BrummelMW\acciones\iAcciones;

class SWGOH extends AccionBasica implements iAcciones
{
    public function retorno(): string
    {
        return "Sin retorno";
    }

    protected function recuperar_json(): array
    {
        return json_decode(file_get_contents("https://swgoh.gg/api/guilds/7217/units/"));
    }
}