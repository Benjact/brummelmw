<?php
namespace BrummelMW\acciones;

class Error extends AccionBasica implements iAcciones
{
    protected $mensaje_error = "";

    public function __construct(string $parametro = "")
    {
        $this->mensaje_error = $parametro;
    }

    public function retorno(): string
    {
        return ($this->mensaje_error != "") ? $this->mensaje_error : "No reconozco esa instruccion";
    }
}