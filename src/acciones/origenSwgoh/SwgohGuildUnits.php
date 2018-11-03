<?php
namespace BrummelMW\acciones\origenSwgoh;

use BrummelMW\acciones\ExcepcionRuta;
use BrummelMW\core\MyListener;
use BrummelMW\core\Utils;
use Exception;

class SwgohGuildUnits implements iSWGOH
{
    public static function recuperarJSON(): array
    {
        $ruta = "https://swgoh.gg/api/guild/" . ID_GREMIO . "/";
        if (!Utils::comprobarExisteRuta($ruta)) {
            throw new ExcepcionRuta($ruta);
        }

        $listener = new MyListener();
        $stream = fopen($ruta, 'r');
        try {
            $parser = new \JsonStreamingParser\Parser($stream, $listener);
            $parser->parse();
            fclose($stream);
        } catch (Exception $e) {
            fclose($stream);
            throw $e;
        }

        return json_decode(file_get_contents($ruta), true);
    }
}