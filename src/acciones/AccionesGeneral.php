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

        if (in_array($primera_palabra, ["/ayuda", "ayuda", "/help", "help"])) {
            if (isset($instruccion_partida[1])) {
                $this->instruccion = new Ayuda($instruccion_partida[1]);
            } else {
                $this->instruccion = new Ayuda();
            }

        } elseif (in_array($primera_palabra, ["/personajes", "personajes"])) {
            if (isset($instruccion_partida[1])) {
                $this->instruccion = new Personajes($instruccion_partida[1]);
            } else {
                $this->instruccion = new Personajes();
            }
        } elseif (in_array($primera_palabra, (new Personajes())->personajes())) {
            $this->instruccion = new Personajes($primera_palabra);

        } elseif (in_array($primera_palabra, ["/excel", "excel"])) {
            $this->instruccion = new Excel();

        } elseif ($instruccion[0] == "/") {
            $this->instruccion = new Error();
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
}