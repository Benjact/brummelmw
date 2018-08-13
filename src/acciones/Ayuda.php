<?php
namespace BrummelMW\acciones;

use BrummelMW\acciones\swgoh\Personajes;

class Ayuda extends AccionBasica implements iAcciones
{
    private $parametro = "";

    public function __construct(string $parametro = "")
    {
        $this->parametro = $parametro;
    }

    public function retorno()
    {
        if ($this->parametro == "personajes") {
            return (new Personajes())->retorno();
        } else {
            return [
                "/personajes: devolverá un listado de personajes posibles",
                "/personajes [NOMBRE_PERSONAJE]: devolverá información de dicho personaje",
                "/personajes [NOMBRE_PERSONAJE] [NUMERO_ESTRELLAS]: indicará cuantos hay en el gremio con esas o más estrellas y quién los tiene",
                "/brummel: indicaciones indispensables para el gremio",
                //"/[NOMBRE_PERSONAJE]: lo mismo que el anterior",
                //"/[NOMBRE_PERSONAJE] [NUMERO_ESTRELLAS]: lo mismo que el anterior",
            ];
        }
    }
}