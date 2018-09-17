<?php
namespace BrummelMW\acciones\origenSwgoh;

use BrummelMW\acciones\ExcepcionRuta;

class SwgohGuildUnits implements iSWGOH
{
    public static function recuperarJSON(): array
    {
        $ruta = "https://swgoh.gg/api/guild/" . ID_GREMIO . "/";
        $file_headers = @get_headers($ruta);
        if ($file_headers[0] == 'HTTP/1.0 404 Not Found') {
            throw new ExcepcionRuta("1 ".$ruta);
        }

        if ($file_headers[0] == 'HTTP/1.0 302 Found' && $file_headers[7] == 'HTTP/1.0 404 Not Found') {
            throw new ExcepcionRuta("2 ".$ruta);
        }

        return json_decode(file_get_contents($ruta), true);
    }
}