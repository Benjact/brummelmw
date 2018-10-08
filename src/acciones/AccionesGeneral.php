<?php
namespace BrummelMW\acciones;

use BrummelMW\acciones\origenExcel\BT;
use BrummelMW\acciones\origenExcel\Excel;
use BrummelMW\acciones\origenSwgoh\Gremio;
use BrummelMW\acciones\origenSwgoh\Hoth;
use BrummelMW\acciones\origenSwgoh\HothNaves;
use BrummelMW\acciones\origenSwgoh\Jugadores;
use BrummelMW\acciones\origenSwgoh\Naves;
use BrummelMW\acciones\origenSwgoh\Personajes;
use BrummelMW\acciones\origenSwgoh\PersonajesInline;
use BrummelMW\acciones\origenSwgoh\SwgohCharacters;
use BrummelMW\acciones\origenSwgoh\SwgohGuildUnits;
use BrummelMW\acciones\origenSwgoh\SwgohShips;
use BrummelMW\bot\iBot;
use BrummelMW\core\PHPFileLoader;
use BrummelMW\response\ObjetoResponse;

class AccionesGeneral
{
    /**
     * @var iAcciones
     */
    protected $instruccion;
    protected $mensajes = [];
    /**
     * @var iBot
     */
    private $bot;

    public function __construct(iBot $bot, bool $inline)
    {
        $this->bot = $bot;
        $instruccion_partida = explode(" ", $bot->mensaje());
        $primera_palabra = $this->devuelvePrimeraPalabra($instruccion_partida);

        if (in_array($primera_palabra, ["ayuda", "help"])) {
            $this->accionAyuda($instruccion_partida);
            return;

        } elseif (in_array($primera_palabra, ["personaje", "personajes"])) {
            $this->accionPersonaje($instruccion_partida);
            return;

        } elseif (in_array($primera_palabra, ["nave", "naves"])) {
            $this->accionNave($instruccion_partida);
            return;

        } elseif (in_array($primera_palabra, ["hoth"])) {
            $this->accionHoth($instruccion_partida);
            return;

        } elseif (in_array($primera_palabra, ["jugador", "jugadores"])) {
            $this->accionJugador($instruccion_partida);
            return;

        } elseif (in_array($primera_palabra, ["gremio"])) {
            $this->accionGremio($instruccion_partida);
            return;

        } elseif (in_array($primera_palabra, ["bt"])) {
            $this->accionBT();
            return;
        }

        $this->mensajes = (new PHPFileLoader)->load(dirname(__DIR__) . "/acciones/mensajes");
        if (in_array($primera_palabra, array_keys($this->mensajes))) {
            if ($primera_palabra == "hola") {
                $this->accionHola($this->mensajes[$primera_palabra], $bot->username());
                return;
            } else {
                $this->accionMensaje($this->mensajes[$primera_palabra]);
                return;
            }
        }

        try {
            $jsonGuildUnits = SwgohGuildUnits::recuperarJSON();
            $personajes = new Personajes("", $jsonGuildUnits, SwgohCharacters::recuperarJSON());
            if (in_array(mb_strtoupper($primera_palabra), $personajes->personajes())) {
                array_unshift($instruccion_partida, "personajes");
                $this->accionPersonaje($instruccion_partida, $inline);
                return;
            }

            $naves = new Naves("", $jsonGuildUnits, SwgohShips::recuperarJSON());
            if (in_array(mb_strtoupper($primera_palabra), $naves->naves())) {
                array_unshift($instruccion_partida, "naves");
                $this->accionNave($instruccion_partida);
                return;
            }
        } catch (ExcepcionRuta $e) {
            $this->accionError($e->getMessage());
        }

        if ($instruccion_partida[0] == "/") {
            $this->accionError();
        }
    }

    /**
     * @return ObjetoResponse
     * @throws ExcepcionAccion
     */
    public function retorno(): ObjetoResponse
    {
        if (!is_null($this->instruccion)) {
            return $this->instruccion->retorno($this->bot->chatId());
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
    protected function accionPersonaje($instruccion_partida, bool $inline = false)
    {
        try {
            if ($inline) {
                $this->instruccion = new PersonajesInline("", SwgohCharacters::recuperarJSON());
            } else {
                $this->instruccion = new Personajes("", SwgohGuildUnits::recuperarJSON(), SwgohCharacters::recuperarJSON());
            }
        } catch (ExcepcionRuta $e) {
            throw new ExcepcionAccion($e->getMessage());
        }

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
        $this->instruccion = new Naves("", SwgohGuildUnits::recuperarJSON(), SwgohShips::recuperarJSON());
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

        try {
            $jsonGuildUnits = SwgohGuildUnits::recuperarJSON();
            $personajes = new Personajes("", $jsonGuildUnits, SwgohCharacters::recuperarJSON());
            $naves = new Naves("", $jsonGuildUnits);
        } catch (ExcepcionRuta $e) {
            throw new ExcepcionAccion($e->getMessage());
        }

        if (in_array(mb_strtoupper($instruccion_partida[1]), $personajes->personajes())) {
            $this->instruccion = new Hoth("", $jsonGuildUnits, SwgohCharacters::recuperarJSON());

        } elseif (in_array(mb_strtoupper($instruccion_partida[1]), $naves->naves())) {
            unset($instruccion_partida[0]);
            array_unshift($instruccion_partida, "hothnaves");
            $this->instruccion = new HothNaves("", $jsonGuildUnits, SwgohShips::recuperarJSON());

        } else {
            throw new ExcepcionAccion("No se identifica ese personaje o nave para pelotones de Hoth. Es posible que no lo tenga nadie del gremio");
        }

        $personaje = mb_strtoupper(str_replace("/", "", $instruccion_partida[1]));
        $this->instruccion->setPersonaje($personaje)
            ->setEstrellas($instruccion_partida[2]);

        if (isset($instruccion_partida[3])) {
            $this->instruccion->setCantidadRetorno($instruccion_partida[3]);
        }
    }

    protected function accionGremio($instruccion_partida)
    {
        try {
            $this->instruccion = new Gremio("", SwgohGuildUnits::recuperarJSON());
        } catch (ExcepcionRuta $e) {
            throw new ExcepcionAccion($e->getMessage());
        }
    }

    /**
     * @param $instruccion_partida
     */
    protected function accionJugador($instruccion_partida)
    {
        try {
            $this->instruccion = new Jugadores("", SwgohGuildUnits::recuperarJSON());
        } catch (ExcepcionRuta $e) {
            throw new ExcepcionAccion($e->getMessage());
        }

        if (isset($instruccion_partida[1])) {
            $jugador = mb_strtoupper($instruccion_partida[1]);
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

    protected function accionBT()
    {
        $this->instruccion = new BT();
    }

    protected function accionError($mensaje = "")
    {
        $this->instruccion = new Error($mensaje);
    }

    /**
     * @param $mensaje
     * @param string $username
     */
    protected function accionHola($mensaje, string $username)
    {
        if (isset($mensaje[$username])) {
            $this->accionMensaje($mensaje[$username]);
        } else {
            $this->accionMensaje($mensaje["desconocido"], ["username" => $username]);
        }
    }

    /**
     * @param $mensaje
     * @param array $sustituir
     */
    protected function accionMensaje($mensaje, array $sustituir = [])
    {
        if (is_array($mensaje)) {
            $texto = array_rand(array_flip($mensaje), 1);
            if (count($sustituir)) {
                foreach ($sustituir as $original => $final) {
                    $texto = str_replace($original, $final, $texto);
                }
            }
            $this->instruccion = new Error($texto);
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