<?php
namespace BrummelMW\acciones\swgoh;

use BrummelMW\acciones\ExcepcionAccion;

class Personajes extends SWGOH
{
    private $personaje = "";

    public function __construct(string $parametro = "")
    {
        $this->personaje = $parametro;
    }

    public function retorno(): string
    {
        $array_personajes = $this->personajes();
        if ($this->personaje == "") {
            return "Personajes posibles:<br>" . implode("<br>", $array_personajes);
        } else {
            if (in_array($this->personaje, $array_personajes)) {
                return "veamos {$this->personaje}";
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
}