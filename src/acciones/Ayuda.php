<?php
namespace BrummelMW\acciones;

use BrummelMW\acciones\origenSwgoh\Personajes;
use BrummelMW\response\ObjetoResponse;

class Ayuda extends AccionBasica implements iAcciones
{
    /**
     * @return ObjetoResponse
     * @throws ExcepcionAccion
     */
    public function retorno(string $id_chat): ObjetoResponse
    {
        if ($this->parametro == "personajes") {
            return (new Personajes())->retorno($id_chat);
        } else {
            $array_ayuda = [
                "/ayuda: mostrará los comandos disponibles en el bot",
                "/personajes o personaje o p: devolverá un listado de personajes posibles",
                "/personajes [NOMBRE_PERSONAJE] [NUMERO_ESTRELLAS] [-e]: devolverá información de dicho personaje. Se puede buscar 
                    %[TROZO_NOMBRE_PERSONAJE] y devolverá solo los coincidentes, si solo devuelve uno será 
                    como si lo hubieramos buscado con el nombre completo, pudiendo añadir el número
                    de estrellas.
                    El número de estrellas es opcional. Puede ir de 0 a 7. Indicando 0 mostrará quien no tiene al personaje 
                    desbloqueado, sinó mostrará los que lo tengan con esa cantidad de estrellas o más.
                    -e: mostrá más info aparte del jugador que lo tenga (nivel, pg, estrellas, gear, zetas)",
                "/[NOMBRE_PERSONAJE]: lo mismo que el anterior, tanto para naves como para personajes",
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
                "chat_id" => $id_chat,
                "parse_mode" => PARSE_MODE,
                "text" => implode(ENTER, $array_ayuda),
            ]);
        }
    }
}