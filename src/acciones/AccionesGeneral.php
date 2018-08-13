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
            array_unshift($instruccion_partida, "personajes");
            $this->accionPersonaje($instruccion_partida);

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
        $this->instruccion = new Personajes();
        if (isset($instruccion_partida[1])) {
            $this->instruccion->setPersonaje(mb_strtoupper($instruccion_partida[1]));
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

    protected function accionError()
    {
        $this->instruccion = new Error();
    }
}