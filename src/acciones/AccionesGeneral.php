<?php
namespace BrummelMW\acciones;

class AccionesGeneral
{
    protected $instruccion;

    public function __construct(string $instruccion)
    {
        $instruccion_partida = explode(" ",$instruccion);
        $primera_palabra = $instruccion_partida[0];
        if ($primera_palabra == "/ayuda") {
            $this->instruccion = new Ayuda();
        } elseif ($primera_palabra == "/excel") {
            $this->instruccion = new Excel();
        } elseif ($instruccion[0] == "/") {
            $this->instruccion = new Error();
        }
    }

    public function retorno(): string
    {
        return $this->instruccion->retorno();
    }
}