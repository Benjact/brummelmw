<?php
namespace BrummelMW\acciones;

use BrummelMW\acciones\swgoh\Personajes;

class Ayuda extends AccionBasica implements iAcciones
{
    private $parametro = "";

    public function __construct(string $parametro = "", array $objetoJSON = [])
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
                "/[NOMBRE_PERSONAJE]: lo mismo que el anterior, tanto para naves como para personajes",
                "/personajes [NOMBRE_PERSONAJE] [NUMERO_ESTRELLAS]: indicará cuantos hay en el gremio con esas o más estrellas y quién los tiene",
                "/[NOMBRE_PERSONAJE] [NUMERO_ESTRELLAS]: lo mismo que el anterior, tanto para naves como para personajes",
                "/naves: devolverá un listado de naves posibles. Se aplican los mismos parámetros que para personajes",
                "/hoth [NOMBRE_PERSONAJE] [NUMERO_ESTRELLAS] (opcional [CANTIDAD]): Por defecto devolverá los 5 jugadores que tengan a ese personaje o nave con menos pg",

                //tonterias
                "/brummel: indicaciones indispensables para el gremio",
                "/autosith: posibles equipos para pasarse la raid sith en auto",
                "/hulio",
                "/herederos",
                "/chancla",
                "/hola",
                //"/jugadores: devolverá un listado de jugadores del gremio registrados en swgoh",
            ];
        }
    }
}