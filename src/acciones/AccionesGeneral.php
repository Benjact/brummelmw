<?php
namespace BrummelMW\acciones;

use BrummelMW\acciones\swgoh\Hoth;
use BrummelMW\acciones\swgoh\HothNaves;
use BrummelMW\acciones\swgoh\Naves;
use BrummelMW\acciones\swgoh\Personajes;

class AccionesGeneral
{
    protected $instruccion;
    protected $mensajes = [
        "brummel" => "No te puedo ayudar, poco Brummel siento en ti. Mejora en la raid sith",
        "brumel" => "No te puedo ayudar, poco Brummel siento en ti. Mejora en la raid sith",
        "autosith" => "Como spaw se entere la has cagao. El último está en imperio",
        "hulio" => "germinado!!",
        "herederos" => "Kiraaaa!!! Mira lo que dicen!!!",
        "chancla" => "La de nndiaz está en un territorio de las guerras",
        "genio" => "Me voy a poner rojo!! Genio mi papi!!"
    ];

    public function __construct(string $instruccion)
    {
        $instruccion_partida = explode(" ", $instruccion);
        $primera_palabra = mb_strtolower($instruccion_partida[0]);

        $primera_palabra = str_replace("/", "", $primera_palabra);
        $primera_palabra = str_replace("@brummelmwbot", "", $primera_palabra);
        $personajes = new Personajes();
        $naves = new Naves();
        if (in_array(mb_strtolower($primera_palabra), ["ayuda", "help"])) {
            $this->accionAyuda($instruccion_partida);

        } elseif (in_array($primera_palabra, ["personajes"])) {
            $this->accionPersonaje($instruccion_partida);

        } elseif (in_array($primera_palabra, ["naves"])) {
            $this->accionNave($instruccion_partida);

        } elseif (in_array(mb_strtoupper($primera_palabra), $personajes->personajes())) {
            array_unshift($instruccion_partida, "personajes");
            $this->accionPersonaje($instruccion_partida);

        } elseif (in_array(mb_strtoupper($primera_palabra), $naves->personajes())) {
            array_unshift($instruccion_partida, "naves");
            $this->accionNave($instruccion_partida);

        } elseif (in_array($primera_palabra, ["hoth"])) {
            if (isset($instruccion_partida[1])) {
                if (in_array(mb_strtoupper($instruccion_partida[1]), $personajes->personajes())) {
                    array_unshift($instruccion_partida, "hoth");
                    $this->accionHoth($instruccion_partida);

                } elseif (in_array(mb_strtoupper($instruccion_partida[1]), $naves->personajes())) {
                    array_unshift($instruccion_partida, "hothnaves");
                    $this->accionHothNaves($instruccion_partida);
                } else {
                    throw new ExcepcionAccion("No se identifica ese personaje o nave para pelotones de Hoth. Es posible que no lo tenga nadie del gremio");
                }
            } else {
                throw new ExcepcionAccion("Debe indicar un personaje o nave");
            }

            if (!isset($instruccion_partida[2])) {
                throw new ExcepcionAccion("Debe indicar el número de estrellas");
            }
            if (!(is_numeric($instruccion_partida[2]) && in_array($instruccion_partida[2], [0,1,2,3,4,5,6,7]))) {
                throw new ExcepcionAccion("Las estrellas deben ser un número comprendido entre 1-7");
            }

        } elseif (in_array($primera_palabra, ["excel"])) {
            $this->accionExcel();

        } elseif (in_array($primera_palabra, array_keys($this->mensajes))) {
            $this->accionError($this->mensajes[$primera_palabra]);

        } elseif ($instruccion_partida[0] == "/") {
            $this->accionError();
        }
    }

    /**
     * @return string|array
     */
    public function retorno()
    {
        try {
            if (!is_null($this->instruccion)) {
                return $this->instruccion->retorno();
            }
            throw new ExcepcionAccion("No reconozco esa instruccion");

        } catch (ExcepcionAccion $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $instruccion_partida
     */
    protected function accionAyuda($instruccion_partida)
    {
        if (isset($instruccion_partida[1])) {
            $this->instruccion = new Ayuda($instruccion_partida[1]);
        } else {
            $this->instruccion = new Ayuda();
        }
    }

    /**
     * @param $instruccion_partida
     */
    protected function accionPersonaje($instruccion_partida)
    {
        $this->instruccion = new Personajes();
        if (isset($instruccion_partida[1])) {
            $personaje = mb_strtoupper(str_replace("/", "", $instruccion_partida[1]));
            $this->instruccion->setPersonaje($personaje);
        }
        if (isset($instruccion_partida[2])) {
            if (is_numeric($instruccion_partida[2]) && in_array($instruccion_partida[2], [0,1,2,3,4,5,6,7])) {
                $this->instruccion->setEstrellas($instruccion_partida[2]);
            } else {
                throw new ExcepcionAccion("Las estrellas deben ser un número comprendido entre 1-7");
            }
        }
    }

    /**
     * @param $instruccion_partida
     */
    protected function accionNave($instruccion_partida)
    {
        $this->instruccion = new Naves();
        if (isset($instruccion_partida[1])) {
            $personaje = mb_strtoupper(str_replace("/", "", $instruccion_partida[1]));
            $this->instruccion->setPersonaje($personaje);
        }
        if (isset($instruccion_partida[2])) {
            if (is_numeric($instruccion_partida[2]) && in_array($instruccion_partida[2], [0,1,2,3,4,5,6,7])) {
                $this->instruccion->setEstrellas($instruccion_partida[2]);
            } else {
                throw new ExcepcionAccion("Las estrellas deben ser un número comprendido entre 1-7");
            }
        }
    }

    /**
     * @param $instruccion_partida
     */
    protected function accionHoth($instruccion_partida)
    {
        $this->instruccion = new Hoth();
        if (isset($instruccion_partida[1])) {
            $personaje = mb_strtoupper(str_replace("/", "", $instruccion_partida[1]));
            $this->instruccion->setPersonaje($personaje);
        }
        if (isset($instruccion_partida[2])) {
            if (is_numeric($instruccion_partida[2]) && in_array($instruccion_partida[2], [0,1,2,3,4,5,6,7])) {
                $this->instruccion->setEstrellas($instruccion_partida[2]);
            } else {
                throw new ExcepcionAccion("Las estrellas deben ser un número comprendido entre 1-7");
            }
        }
    }

    /**
     * @param $instruccion_partida
     */
    protected function accionHothNaves($instruccion_partida)
    {
        $this->instruccion = new HothNaves();
        if (isset($instruccion_partida[1])) {
            $personaje = mb_strtoupper(str_replace("/", "", $instruccion_partida[1]));
            $this->instruccion->setPersonaje($personaje);
        }
        if (isset($instruccion_partida[2])) {
            if (is_numeric($instruccion_partida[2]) && in_array($instruccion_partida[2], [0,1,2,3,4,5,6,7])) {
                $this->instruccion->setEstrellas($instruccion_partida[2]);
            } else {
                throw new ExcepcionAccion("Las estrellas deben ser un número comprendido entre 1-7");
            }
        }
    }

    protected function accionExcel()
    {
        $this->instruccion = new Excel();
    }

    protected function accionError($mensaje = "")
    {
        $this->instruccion = new Error($mensaje);
    }
}