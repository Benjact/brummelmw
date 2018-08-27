<?php
namespace BrummelMW\acciones\origenSwgoh;

use BrummelMW\acciones\AccionBasica;
use BrummelMW\acciones\ExcepcionAccion;
use BrummelMW\core\Utils;
use BrummelMW\response\ObjetoResponse;

class Jugadores extends AccionBasica
{
    protected $jugador = "";
    /**
     * @var array
     */
    private $objetoJSON;
    /**
     * @var string
     */
    private $parametro;

    public function __construct(string $parametro = "", array $objetoJSON = [])
    {
        $this->objetoJSON = $this->recuperar_json($objetoJSON);
        $this->parametro = $parametro;
    }

    protected function recuperar_json(array $recuperar_json): array
    {
        return $recuperar_json;
    }

    /**
     * @param string $jugador
     * @return Personajes
     */
    public function setJugador(string $jugador): Jugadores
    {
        $this->jugador = mb_strtoupper($jugador);
        return $this;
    }

    /**
     * @param string $parametro
     */
    public function setParametro(string $parametro)
    {
        $this->parametro = $parametro;
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
        foreach ($this->objetoJSON as $personaje => $jugadores) {
            foreach ($jugadores as $jugador) {
                $nombre_judador = str_replace(" ", "_", mb_strtoupper($jugador["player"]));
                if (!isset($array_jugadores[$nombre_judador])) {
                    $array_jugadores[$nombre_judador] = [
                        "pg" => 0,
                        "pg_personajes" => 0,
                        "pg_naves" => 0,
                        "personajes" => [],
                        "naves" => [],
                    ];
                }

                $array_jugadores[$nombre_judador]["pg"] += $jugador["power"];
                if ($jugador["combat_type"] == 1) {
                    $array_jugadores[$nombre_judador]["pg_personajes"] += $jugador["power"];
                    $array_jugadores[$nombre_judador]["personajes"][] = $personaje;
                } else {
                    $array_jugadores[$nombre_judador]["pg_naves"] += $jugador["power"];
                    $array_jugadores[$nombre_judador]["naves"][] = $personaje;
                }
            }
        }
        return $array_jugadores;
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
            $datos_retorno[] = BOLD."PERSONAJES".BOLD_CERRAR;
            $datos_retorno = array_merge($datos_retorno, $datos_jugador["personajes"]);

            $datos_retorno[] = "";
            $datos_retorno[] = BOLD."NAVES".BOLD_CERRAR;
            $datos_retorno = array_merge($datos_retorno, $datos_jugador["naves"]);
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