<?php
namespace BrummelMW\acciones\swgoh;

use BrummelMW\acciones\ExcepcionAccion;

class Personajes extends SWGOH
{
    private $personaje = "";
    private $estrellas = 0;

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
     * @param string $estrellas
     * @return Personajes
     */
    public function setEstrellas(string $estrellas): Personajes
    {
        $this->estrellas = $estrellas;
        return $this;
    }

    public function retorno()
    {
        $array_personajes = $this->personajes();

        if ($this->personaje == "") {
            return $array_personajes;
        } else {
            if (in_array($this->personaje, $array_personajes)) {
                $datos_personajes = $this->recuperar_json();
                $datos_personaje = $datos_personajes[$this->personaje];
                if ($this->estrellas != 0) {
                    return $this->infoPersonajeEstrellas($datos_personaje, $this->estrellas);
                } else {
                    return $this->infoPersonaje($datos_personaje);
                }
            } else {
                return new ExcepcionAccion("Personaje {$this->personaje} no encontrado");
            }
        }
    }

    public function personajes()
    {
        return array_keys($this->recuperar_json());
    }

    protected function recuperar_json(string $personaje = ""): array
    {
        $recuperar_json = parent::recuperar_json();

        if ($personaje) {
            return $recuperar_json[$personaje];
        } else {
            return $recuperar_json;
        }
    }

    private function infoPersonaje(array $datos_personaje)
    {
        return $this->infoPersonajeEstrellas($datos_personaje);
    }

    private function infoPersonajeEstrellas(array $datos_personaje, int $estrellas = 1)
    {
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
            if ($jugador["gear_level"] >= $estrellas) {
                $recopilacion[$jugador["gear_level"]]["cantidad"] += 1;
                if (!isset($recopilacion[$jugador["gear_level"]]["jugadores"])) {
                    $recopilacion[$jugador["gear_level"]]["jugadores"] = [];
                }
                $recopilacion[$jugador["gear_level"]]["jugadores"][] = $jugador["player"]." lvl:".$jugador["level"];
            }
        }

        $cantidad = array_sum(array_map(function($cantidad) { return $cantidad["cantidad"]; }, $recopilacion));
        $datos_retorno = ["{$this->personaje} {$cantidad} en el gremio"];
        foreach ($recopilacion as $estrellas => $datos) {
            $datos_retorno[] = "{$estrellas}* => {$datos["cantidad"]} en el gremio";
            foreach ($datos["jugadores"] as $jugador) {
                $datos_retorno[] = $jugador;
            }
            $datos_retorno[] = "";
        }
        return $datos_retorno;
    }


}