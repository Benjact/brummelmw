<?php
namespace BrummelMW\acciones\origenSwgoh;

use BrummelMW\acciones\AccionBasica;
use BrummelMW\acciones\ExcepcionAccion;
use BrummelMW\core\Utils;
use BrummelMW\response\ObjetoResponse;

class Jugadores extends AccionBasica
{
    protected $jugador = "";

    public function __construct(string $parametro = "", array $objetoJSON = [])
    {
        parent::__construct($parametro, $objetoJSON);

        $this->objetoJSON = $this->recuperar_json($objetoJSON);
    }

    protected function recuperar_json(array $recuperar_json): array
    {
        return $recuperar_json;
    }

    /**
     * @param string $jugador
     * @return Jugadores
     */
    public function setJugador(string $jugador): Jugadores
    {
        $this->jugador = mb_strtoupper($jugador);
        return $this;
    }

    /**
     * @param string $parametro
     * @return Jugadores
     */
    public function setParametro(string $parametro)
    {
        $this->parametro = $parametro;
        return $this;
    }

    /**
     * @param string $id_chat
     * @return ObjetoResponse
     * @throws ExcepcionAccion
     */
    public function retorno(string $id_chat): ObjetoResponse
    {
        $array_jugadores = $this->jugadores();
        $array_key_jugadores = array_keys($array_jugadores);

        if ($this->jugador == "") {
            asort($array_key_jugadores);

            return $this->retornoObjeto($id_chat, $array_key_jugadores);

        } elseif ($this->jugador[0] == "%") {
            $coincidencia = str_replace("%", "", $this->jugador);
            $array_jugadores_coincidentes = Utils::filtrar($array_key_jugadores, $coincidencia);

            asort($array_jugadores_coincidentes);
            return $this->retornoObjeto($id_chat, $array_jugadores_coincidentes);

        } else {
            if (in_array($this->jugador, array_keys($array_jugadores))) {
                return $this->retornoObjeto($id_chat, $this->infoJugador($array_jugadores[$this->jugador]));

            } else {
                throw new ExcepcionAccion($this->avisoJugadorNoEncontrado());
            }
        }
    }

    protected function retornoObjeto(string $id_chat, array $array_mensaje): ObjetoResponse
    {
        return new ObjetoResponse(ObjetoResponse::MENSAJE, [
            "chat_id" => $id_chat,
            "parse_mode" => PARSE_MODE,
            "text" => implode(ENTER, $array_mensaje),
        ]);
    }

    public function jugadores()
    {
        $array_jugadores = [];
        foreach ($this->objetoJSON["players"] as $jugador) {
            $nombre_judador = self::limpiar_nombre($jugador["data"]["name"]);
            if (!isset($array_jugadores[$nombre_judador])) {
                $array_jugadores[$nombre_judador] = [
                    "pg" => 0,
                    "pg_personajes" => 0,
                    "pg_naves" => 0,
                    "personajes" => [],
                ];
            }

            $array_jugadores[$nombre_judador]["pg"] = $jugador["data"]["galactic_power"];
            $array_jugadores[$nombre_judador]["pg_personajes"] = $jugador["data"]["character_galactic_power"];
            $array_jugadores[$nombre_judador]["pg_naves"] = $jugador["data"]["ship_galactic_power"];
            foreach ($jugador["units"] as $personaje) {
                $array_jugadores[$nombre_judador]["personajes"][] = $personaje["data"]["base_id"];
            }
        }
        return $array_jugadores;
    }

    /**
     * @param string $nombre
     * @return string
     */
    public static function limpiar_nombre(string $nombre)
    {
        return str_replace(" ", "_", mb_strtoupper($nombre));
    }

    protected function infoJugador(array $datos_jugador)
    {
        $datos_retorno = [
            BOLD."PG TOTAL {$this->jugador}:".BOLD_CERRAR." ".$datos_jugador["pg"],
            "PG PERSONAJES: ".$datos_jugador["pg_personajes"],
            "PG NAVES: ".$datos_jugador["pg_naves"],
        ];
        if ($this->parametro == "extendido") {
            $datos_retorno[] = "";
            $datos_retorno[] = BOLD."PERSONAJES Y NAVES".BOLD_CERRAR;
            $datos_retorno = array_merge($datos_retorno, $datos_jugador["personajes"]);
        }
        return $datos_retorno;
    }

    /**
     * @return string
     */
    protected function avisoJugadorNoEncontrado(): string
    {
        return "Jugador {$this->jugador} no encontrado";
    }
}