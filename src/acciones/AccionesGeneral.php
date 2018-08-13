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
        if ($primera_palabra == "/ayuda") {
            $this->instruccion = new Ayuda();

        } elseif ($primera_palabra == "/personajes") {
            if (isset($instruccion_partida[1])) {
                $this->instruccion = new Personajes($instruccion_partida[1]);
            } else {
                $this->instruccion = new Personajes();
            }

        } elseif ($primera_palabra == "/excel") {
            $this->instruccion = new Excel();

        } elseif ($instruccion[0] == "/") {
            $this->instruccion = new Error();
        }
    }

    public function retorno(): string
    {
        try {
            return $this->instruccion->retorno();
        } catch (ExcepcionAccion $e) {
            return $e->getMessage();
        }
    }
}