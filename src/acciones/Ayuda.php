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

    /**
     * @return array|string
     * @throws ExcepcionAccion
     */
    public function retorno()
    {
        if ($this->parametro == "personajes") {
            return (new Personajes())->retorno();
        } else {
            return [
                "/ayuda: mostrará los comandos disponibles en el bot",
                "/ayuda personajes: devolverá un listado de personajes posibles",
                //"/ayuda jugadores: devolverá un listado de jugadores del gremio registrados en swgoh",
                "/personajes: devolverá un listado de personajes posibles",
                "/personajes [NOMBRE_PERSONAJE]: devolverá información de dicho personaje",
                "/[NOMBRE_PERSONAJE]: lo mismo que el anterior",
                "/personajes [NOMBRE_PERSONAJE] [NUMERO_ESTRELLAS]: indicará cuantos hay en el gremio con esas o más estrellas y quién los tiene",
                "/[NOMBRE_PERSONAJE] [NUMERO_ESTRELLAS]: lo mismo que el anterior",
                "/brummel: indicaciones indispensables para el gremio",
                "/autosith: posibles equipos para pasarse la raid sith en auto",
                //"/jugadores: devolverá un listado de jugadores del gremio registrados en swgoh",
            ];
        }
    }
}