<?php
namespace BrummelMW\acciones;

use BrummelMW\acciones\origenSwgoh\Personajes;
use BrummelMW\response\ObjetoResponse;

class Ayuda extends AccionBasica implements iAcciones
{
    private $parametro = "";

    public function __construct(string $parametro = "", array $objetoJSON = [])
    {
        $this->parametro = $parametro;
    }

    /**
     * @return ObjetoResponse
     * @throws ExcepcionAccion
     */
    public function retorno(): ObjetoResponse
    {
        if ($this->parametro == "personajes") {
            return (new Personajes())->retorno();
        } else {
            $array_ayuda = [
                "/ayuda: mostrará los comandos disponibles en el bot",
                "/personajes o personaje: devolverá un listado de personajes posibles",
                "/personajes [NOMBRE_PERSONAJE]: devolverá información de dicho personaje. Se puede buscar 
                    %[TROZO_NOMBRE_PERSONAJE] y devolverá solo los coincidentes",
                "/[NOMBRE_PERSONAJE]: lo mismo que el anterior, tanto para naves como para personajes",
                "/personajes [NOMBRE_PERSONAJE] [NUMERO_ESTRELLAS]: indicará cuantos hay en el gremio con esas o 
                    más estrellas y quién los tiene",
                "/[NOMBRE_PERSONAJE] [NUMERO_ESTRELLAS]: lo mismo que el anterior, tanto para naves como 
                    para personajes",
                "/naves o nave: devolverá un listado de naves posibles. Se aplican los mismos parámetros que 
                    para personajes",
                "/naves [NOMBRE_NAVE]: devolverá información de dicho personaje",
                "/jugadores o jugador: devolverá un listado de jugadores posibles",
                "/jugadores [NOMBRE_JUGADOR] (opcional extendido): devolverá pg total y de personajes y naves. 
                    Extendido devolverá un listado de su roster separado en personajes y naves",
                "/hoth [NOMBRE_PERSONAJE] [NUMERO_ESTRELLAS] (opcional [CANTIDAD]): Por defecto devolverá los 5 
                    jugadores que tengan a ese personaje o nave con menos pg",

                //tonterias
                "/brummel: indicaciones indispensables para el gremio",
                "/autosith: posibles equipos para pasarse la raid sith en auto",
                "/hulio",
                "/herederos",
                "/chancla",
                "/hola",
                //"/jugadores: devolverá un listado de jugadores del gremio registrados en swgoh",
            ];

            return new ObjetoResponse(ObjetoResponse::MENSAJE, [
                "parse_mode" => PARSE_MODE,
                "text" => implode(ENTER, $array_ayuda),
            ]);
        }
    }
}