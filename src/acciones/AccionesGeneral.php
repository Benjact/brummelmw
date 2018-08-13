<?php
namespace BrummelMW\acciones;

use BrummelMW\acciones\swgoh\Personajes;

class AccionesGeneral
{
    protected $instruccion;

    public function __construct(string $instruccion)
    {
        $instruccion_partida = explode(" ", $instruccion);
        $primera_palabra = $instruccion_partida[0];

        $primera_palabra = str_replace("/", "", $primera_palabra);
        if (in_array(mb_strtolower($primera_palabra), ["ayuda", "help"])) {
            $this->accionAyuda($instruccion_partida);

        } elseif (in_array(mb_strtolower($primera_palabra), ["personajes"])) {
            $this->accionPersonaje($instruccion_partida);

        } elseif (in_array(mb_strtoupper($primera_palabra), (new Personajes())->personajes())) {
            $this->accionPersonaje(["personajes", $primera_palabra]);

        } elseif (in_array(mb_strtolower($primera_palabra), ["excel"])) {
            $this->accionExcel();

        } elseif ($instruccion_partida[0] == "/") {
            $this->accionError();
        }
    }

    public function retorno()
    {
        try {
            return $this->instruccion->retorno();
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
        if (isset($instruccion_partida[1])) {
            $this->instruccion = new Personajes(mb_strtoupper($instruccion_partida[1]));
        } else {
            $this->instruccion = new Personajes();
        }
    }

    protected function accionExcel()
    {
        $this->instruccion = new Excel();
    }

    protected function accionError()
    {
        $this->instruccion = new Error();
    }
}