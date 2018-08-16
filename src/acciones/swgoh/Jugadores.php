<?php
namespace BrummelMW\acciones\swgoh;

use BrummelMW\acciones\AccionBasica;
use BrummelMW\acciones\ExcepcionAccion;

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
     * @return array|string
     * @throws ExcepcionAccion
     */
    public function retorno()
    {
        $array_jugadores = $this->jugadores();

        if ($this->jugador == "") {
            asort($array_jugadores);
            return $array_jugadores;
        } else {
            if (in_array($this->jugador, $array_jugadores)) {
                $datos_jugador = $this->objetoJSON[$this->jugador];
                return $this->infoJugador($datos_jugador);

            } else {
                throw new ExcepcionAccion($this->avisoJugadorNoEncontrado());
            }
        }
    }

    public function jugadores()
    {
        $array_jugadores = [];
        foreach ($this->objetoJSON as $personaje => $jugadores) {
            foreach ($jugadores as $jugador) {
                $nombre_judador = mb_strtolower($jugador["player"]);
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
        return array_keys($array_jugadores);
    }

    protected function infoJugador(array $datos_jugador)
    {
        $datos_retorno = [
            "<b>PG TOTAL {$this->jugador}:</b> ".$datos_jugador["pg"],
            "PG PERSONAJES: ".$datos_jugador["pg_personajes"],
            "PG NAVES: ".$datos_jugador["pg_naves"],
        ];
        if ($this->parametro == "extendido") {
            $datos_retorno[] = "";
            $datos_retorno[] = "<b>PERSONAJES</b>";
            $datos_retorno = array_merge($datos_retorno, $datos_jugador["personajes"]);

            $datos_retorno[] = "";
            $datos_retorno[] = "<b>NAVES</b>";
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