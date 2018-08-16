<?php
namespace BrummelMW\acciones;

use BrummelMW\acciones\swgoh\Hoth;
use BrummelMW\acciones\swgoh\HothNaves;
use BrummelMW\acciones\swgoh\Naves;
use BrummelMW\acciones\swgoh\Personajes;
use BrummelMW\acciones\swgoh\SwgohGuildUnits;
use BrummelMW\core\PHPFileLoader;
use function Composer\Autoload\includeFile;

class AccionesGeneral
{
    protected $instruccion;
    protected $mensajes = [];

    public function __construct(string $instruccion, string $username)
    {
        $instruccion_partida = explode(" ", $instruccion);
        $primera_palabra = $this->devuelvePrimeraPalabra($instruccion_partida);

        if (in_array($primera_palabra, ["ayuda", "help"])) {
            $this->accionAyuda($instruccion_partida);
            return;

        } elseif (in_array($primera_palabra, ["personajes"])) {
            $this->accionPersonaje($instruccion_partida);
            return;

        } elseif (in_array($primera_palabra, ["naves"])) {
            $this->accionNave($instruccion_partida);
            return;

        } elseif (in_array($primera_palabra, ["hoth"])) {
            $this->accionHoth($instruccion_partida);
            return;

        } elseif (in_array($primera_palabra, ["jugador"])) {
            $this->accionJugador($instruccion_partida);
            return;

        /*} elseif (in_array($primera_palabra, ["excel"])) {
            $this->accionExcel();*/
        }

        $this->mensajes = (new PHPFileLoader)->load(dirname(__DIR__) . "/acciones/mensajes");
        if (in_array($primera_palabra, array_keys($this->mensajes))) {
            if ($primera_palabra == "hola") {
                $this->accionHola($this->mensajes[$primera_palabra], $username);
                return;
            } else {
                $this->accionMensaje($this->mensajes[$primera_palabra]);
                return;
            }
        }

        $jsonGuildUnits = SwgohGuildUnits::recuperarJSON();
        $personajes = new Personajes("", $jsonGuildUnits);
        if (in_array(mb_strtoupper($primera_palabra), $personajes->personajes())) {
            array_unshift($instruccion_partida, "personajes");
            $this->accionPersonaje($instruccion_partida);
            return;
        }

        $naves = new Naves("", $jsonGuildUnits);
        if (in_array(mb_strtoupper($primera_palabra), $naves->naves())) {
            array_unshift($instruccion_partida, "naves");
            $this->accionNave($instruccion_partida);
            return;
        }

        if ($instruccion_partida[0] == "/") {
            $this->accionError();
        }
    }

    /**
     * @return string|array
     */
    public function retorno()
    {
        if (!is_null($this->instruccion)) {
            return $this->instruccion->retorno();
        }
        throw new ExcepcionAccion("No reconozco esa instruccion");
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
        $this->instruccion = new Personajes("", SwgohGuildUnits::recuperarJSON());
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
        $this->instruccion = new Naves("", SwgohGuildUnits::recuperarJSON());
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
        if (!isset($instruccion_partida[1])) {
            throw new ExcepcionAccion("Debe indicar un personaje o nave");
        }
        if (!isset($instruccion_partida[2])) {
            throw new ExcepcionAccion("Debe indicar el número de estrellas");
        }
        if (!(is_numeric($instruccion_partida[2]) && in_array($instruccion_partida[2], [0,1,2,3,4,5,6,7]))) {
            throw new ExcepcionAccion("Las estrellas deben ser un número comprendido entre 1-7");
        }

        $jsonGuildUnits = SwgohGuildUnits::recuperarJSON();
        $personajes = new Personajes("", $jsonGuildUnits);
        $naves = new Naves("", $jsonGuildUnits);

        if (in_array(mb_strtoupper($instruccion_partida[1]), $personajes->personajes())) {
            $this->instruccion = new Hoth("", $jsonGuildUnits);

        } elseif (in_array(mb_strtoupper($instruccion_partida[1]), $naves->naves())) {
            unset($instruccion_partida[0]);
            array_unshift($instruccion_partida, "hothnaves");
            $this->instruccion = new HothNaves("", $jsonGuildUnits);

        } else {
            throw new ExcepcionAccion("No se identifica ese personaje o nave para pelotones de Hoth. Es posible que no lo tenga nadie del gremio");
        }

        $personaje = mb_strtoupper(str_replace("/", "", $instruccion_partida[1]));
        $this->instruccion->setPersonaje($personaje);

        $this->instruccion->setEstrellas($instruccion_partida[2]);

        if (isset($instruccion_partida[3])) {
            $this->instruccion->setCantidadRetorno($instruccion_partida[3]);
        }
    }

    /**
     * @param $instruccion_partida
     */
    protected function accionJugador($instruccion_partida)
    {
        $this->instruccion = new Jugadores("", SwgohGuildUnits::recuperarJSON());
        if (isset($instruccion_partida[1])) {
            $jugador = mb_strtoupper(str_replace("/", "", $instruccion_partida[1]));
            $this->instruccion->setJugador($jugador);
        }
        if (isset($instruccion_partida[2])) {
            if ($instruccion_partida[2] == "extendido") {
                $this->instruccion->setParametro($instruccion_partida[2]);
            } else {
                throw new ExcepcionAccion("El segundo parametro solo puede ser 'extendido'");
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

    /**
     * @param $instruccion_partida
     */
    protected function accionHola($mensaje, $username)
    {
        if (isset($mensaje[$username])) {
            $this->accionMensaje($mensaje[$username]);
        } else {
            $this->accionMensaje("¿Tú quien eres, {$username}?");
        }
    }

    /**
     * @param $instruccion_partida
     */
    protected function accionMensaje($mensaje)
    {
        if (is_array($mensaje)) {
            $this->instruccion = new Error(array_rand(array_flip($mensaje), 1));
        } else {
            $this->instruccion = new Error($mensaje);
        }
    }

    /**
     * @param $instruccion_partida
     * @return mixed|null|string|string[]
     */
    protected function devuelvePrimeraPalabra($instruccion_partida)
    {
        $primera_palabra = mb_strtolower($instruccion_partida[0]);

        $primera_palabra = str_replace("/", "", $primera_palabra);
        $primera_palabra = str_replace("@brummelmwbot", "", $primera_palabra);
        return $primera_palabra;
    }
}