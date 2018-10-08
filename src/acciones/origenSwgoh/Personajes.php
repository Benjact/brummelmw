<?php

namespace BrummelMW\acciones\origenSwgoh;

use BrummelMW\acciones\AccionBasica;
use BrummelMW\acciones\AccionCompuesta;
use BrummelMW\acciones\ExcepcionAccion;
use BrummelMW\core\Utils;
use BrummelMW\response\ObjetoResponse;

class Personajes extends AccionCompuesta
{
    protected $personaje = "";
    protected $estrellas = 1;
    protected $combat_type = 1;

    /**
     * @param string $personaje
     * @return Personajes
     */
    public function setPersonaje(string $personaje): Personajes
    {
        $this->personaje = mb_strtoupper($personaje);
        echo "setPersonaje: ".$this->personaje ." vs ".$personaje;
        return $this;
    }

    /**
     * @param int $estrellas
     * @return Personajes
     */
    public function setEstrellas(int $estrellas): Personajes
    {
        $this->estrellas = $estrellas;
        return $this;
    }

    /**
     * @param string $id_chat
     * @return ObjetoResponse
     * @throws ExcepcionAccion
     */
    public function retorno(string $id_chat): ObjetoResponse
    {
        $array_personajes = $this->personajes();

        if ($this->personaje == "") {
            asort($array_personajes);
            return $this->retornoObjeto($id_chat, $array_personajes);
        } elseif ($this->personaje[0] == "%") {
            $coincidencia = str_replace("%", "", $this->personaje);
            $array_personajes_coincidentes = Utils::filtrar($array_personajes, $coincidencia);
            if (count($array_personajes_coincidentes) == 1) {
                $this->setPersonaje(str_replace(BOLD, "", str_replace(BOLD_CERRAR, "", $array_personajes_coincidentes[0])));
                return $this->retorno($id_chat);
            } else {
                asort($array_personajes_coincidentes);
                return $this->retornoObjeto($id_chat, $array_personajes_coincidentes);
            }
        } else {
            if (in_array($this->personaje, $array_personajes)) {
                if ($this->estrellas != 1) {
                    return $this->retornoObjeto($id_chat, $this->infoPersonajeEstrellas($this->objetoJSON, $this->estrellas), "markdown");
                } else {
                    return $this->retornoObjeto($id_chat, $this->infoPersonaje($this->objetoJSON), "markdown");
                }
            } else {
                throw new ExcepcionAccion($this->avisoPersonajeNoEncontrado());
            }
        }
    }

    protected function retornoObjeto(string $id_chat, array $array_mensaje, string $parse_mode = PARSE_MODE): ObjetoResponse
    {
        return new ObjetoResponse(ObjetoResponse::MENSAJE, [
            "chat_id" => $id_chat,
            "parse_mode" => $parse_mode,
            "text" => implode("\n", $array_mensaje),
        ]);
    }


    public function personajes()
    {
        $personajes = [];
        foreach ($this->objetoJSONextra as $personaje) {
            if ($personaje["combat_type"] == $this->combat_type) {
                $personajes[] = $personaje["base_id"];
            }
        }
        return $personajes;
    }

    protected function infoPersonaje(array $datos_personaje)
    {
        return $this->infoPersonajeEstrellas($datos_personaje);
    }

    protected function infoPersonajeEstrellas(array $datos_gremio, int $estrellas = 1)
    {
        /*$json = {
            "players":[{
                "units":[{
                    "data":{
                        "gear_level":9,
                        "gear":[
                            {"slot":0,"is_obtained":true,"base_id":"126"},
                            {"slot":1,"is_obtained":false,"base_id":"121"},
                            {"slot":2,"is_obtained":false,"base_id":"125"},
                            {"slot":3,"is_obtained":false,"base_id":"108"},
                            {"slot":4,"is_obtained":false,"base_id":"112"},
                            {"slot":5,"is_obtained":false,"base_id":"109"}
                        ],
                        "power":13978,
                        "level":85,
                        "url":"/p/687829488/characters/garazeb-zeb-orrelios",
                        "rarity":7,
                        "base_id":"ZEBS3",
                        "stats":{
                            "27":0.1, "28":22039.0, "40":0.0, "1":23981.0, "3":644, "2":983,"5":128.0,"4":493,"7":1325.0,"6":2006.0,"9":14.601473543201607,"8":34.17656169334022,"39":0.0,"12":2.0,"11":0.0,"10":20.0,"13":2.0,"38":0.0,"15":19.69,"14":40.690000000000005,"17":0.3256,"16":1.5,"18":0.5321,"37":0.0
                        },
                        "zeta_abilities":[]
                    }
                }],
                "data":{
                    "ship_galactic_power":1254582,
                    "arena_leader_base_id":"REYJEDITRAINING",
                    "name":"SpawTadeus",
                    "ally_code":411625797,
                    "galactic_power":3131494,
                    "level":85,
                    "character_galactic_power":1876912,
                    "arena_rank":156,
                    "url":"/p/411625797/"}
                }
            }],
            "data":{
                "name":"MadridWars",
                "member_count":47,
                "galactic_power":104150254,
                "rank":0,
                "profile_count":47,
                "id":7217
            }
        };*/


        $datos_retorno = [];

        $texto_imagen = $this->buscarImagen($this->personaje);
        if ($texto_imagen != "") {
            $datos_retorno[] = $texto_imagen;
        }

        $recopilacion = [
            1 => ["cantidad" => 0],
            2 => ["cantidad" => 0],
            3 => ["cantidad" => 0],
            4 => ["cantidad" => 0],
            5 => ["cantidad" => 0],
            6 => ["cantidad" => 0],
            7 => ["cantidad" => 0],
        ];
        foreach ($datos_gremio["players"] as $jugador) {
            foreach ($jugador["units"] as $personaje) {
                if ($this->personaje == $personaje["data"]["base_id"]) {
                    $rarity = $personaje["data"]["rarity"];

                    $recopilacion[$rarity]["cantidad"] += 1;
                    if (!isset($recopilacion[$rarity]["jugadores"])) {
                        $recopilacion[$rarity]["jugadores"] = [];
                    }
                    $recopilacion[$rarity]["jugadores"][] = $jugador["data"]["name"];
                }
            }
        }

        $cantidad_total = array_sum(array_map(function ($cantidad) {
            return $cantidad["cantidad"];
        }, $recopilacion));

        if ($estrellas > 1) {
            $cantidad = array_sum(array_map(function ($estrella, $cantidad) use ($estrellas) {
                if ($estrella >= $estrellas) {
                    return $cantidad["cantidad"];
                }
                return 0;
            }, array_keys($recopilacion), $recopilacion));
            $datos_retorno[] = BOLD_MD . "{$cantidad}/{$cantidad_total} en el gremio" . BOLD_CERRAR_MD;
        } elseif ($estrellas == 0) {
            $datos_retorno[] = "`" . (50 - $cantidad_total) . " no lo tienen desbloqueado`";
            $jugadores_con_personaje = [];
            foreach ($recopilacion as $estrellas_recopilacion => $datos) {
                if (!empty($datos["jugadores"])) {
                    foreach ($datos["jugadores"] as $jugador) {
                        $jugadores_con_personaje[] = Jugadores::limpiar_nombre($jugador);
                    }
                }
            }

            $total_jugadores = array_keys((new Jugadores("", $this->objetoJSON))->jugadores());
            $datos_retorno = array_merge(
                $datos_retorno,
                array_values(array_diff($total_jugadores, $jugadores_con_personaje))
            );

        } else {
            $datos_retorno[] = BOLD_MD . "{$cantidad_total} en el gremio" . BOLD_CERRAR_MD;
        }

        if ($estrellas != 0) {
            foreach ($recopilacion as $estrellas_recopilacion => $datos) {
                if ($estrellas_recopilacion >= $estrellas) {
                    $datos_retorno[] = BOLD_MD . $estrellas_recopilacion . BOLD_CERRAR_MD . " => {$datos["cantidad"]} en el gremio";
                    if (!empty($datos["jugadores"])) {
                        foreach ($datos["jugadores"] as $jugador) {
                            $datos_retorno[] = $jugador;
                        }
                    }
                    $datos_retorno[] = "";
                }
            }
        }
        return $datos_retorno;
    }

    /**
     * @return string
     */
    protected function avisoPersonajeNoEncontrado(): string
    {
        return "Personaje {$this->personaje} no encontrado";
    }

    protected function buscarImagen(string $idPersonaje)
    {
        foreach ($this->objetoJSONextra as $personaje) {
            if ($personaje["base_id"] == $idPersonaje) {
                return "[$idPersonaje](https:{$personaje["image"]})";
            }
        }

        return "";
    }
}