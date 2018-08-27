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
    protected $estrellas = 0;
    protected $combat_type = 1;

    public function __construct(string $parametro = "", array $objetoJSON = [], array $objetoJSONextra = [])
    {
        parent::__construct($parametro, $objetoJSON, $objetoJSONextra);

        $this->objetoJSON = $this->recuperar_json($objetoJSON);
    }

    protected function recuperar_json(array $recuperar_json): array
    {
        foreach ($recuperar_json as $nombre => $personaje) {
            if ($personaje[0]["combat_type"] != $this->combat_type) {
                unset($recuperar_json[$nombre]);
            }
        }

        return $recuperar_json;
    }

    /**
     * @param string $personaje
     * @return Personajes
     */
    public function setPersonaje(string $personaje): Personajes
    {
        $this->personaje = mb_strtoupper($personaje);
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

            asort($array_personajes_coincidentes);
            return $this->retornoObjeto($id_chat, $array_personajes_coincidentes);
        } else {
            if (in_array($this->personaje, $array_personajes)) {
                $datos_personaje = $this->objetoJSON[$this->personaje];
                if ($this->estrellas != 0) {
                    return $this->retornoObjeto($id_chat, $this->infoPersonajeEstrellas($datos_personaje, $this->estrellas), "markdown");
                } else {
                    return $this->retornoObjeto($id_chat, $this->infoPersonaje($datos_personaje), "markdown");
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
            "text" => implode("\\n", $array_mensaje),
        ]);
    }


    public function personajes()
    {
        return array_keys($this->objetoJSON);
    }

    protected function infoPersonaje(array $datos_personaje)
    {
        return $this->infoPersonajeEstrellas($datos_personaje);
    }

    protected function infoPersonajeEstrellas(array $datos_personaje, int $estrellas = 1)
    {

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
        foreach ($datos_personaje as $jugador) {
            $recopilacion[$jugador["rarity"]]["cantidad"] += 1;
            if (!isset($recopilacion[$jugador["rarity"]]["jugadores"])) {
                $recopilacion[$jugador["rarity"]]["jugadores"] = [];
            }
            $recopilacion[$jugador["rarity"]]["jugadores"][] = $jugador["player"];
            //." lvl:".$jugador["level"]." gear:".$jugador["gear_level"];
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
            $datos_retorno[] = BOLD_MD."{$cantidad}/{$cantidad_total} en el gremio".BOLD_CERRAR_MD;
        } else {
            $datos_retorno[] = BOLD_MD."{$cantidad_total} en el gremio".BOLD_CERRAR_MD;
        }

        foreach ($recopilacion as $estrellas_recopilacion => $datos) {
            if ($estrellas_recopilacion >= $estrellas) {
                $datos_retorno[] = BOLD_MD.$estrellas_recopilacion.ASTERISCO_MD." => {$datos["cantidad"]} en el gremio".BOLD_CERRAR_MD;
                if (!empty($datos["jugadores"])) {
                    foreach ($datos["jugadores"] as $jugador) {
                        $datos_retorno[] = $jugador;
                    }
                }
                $datos_retorno[] = "";
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