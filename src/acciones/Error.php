<?php
namespace BrummelMW\acciones;

class Error extends AccionBasica implements iAcciones
{
    protected $mensaje_error = "";

    public function __construct(string $parametro = "")
    {
        $this->mensaje_error = $parametro;
    }

    public function retorno()
    {
        if ($this->mensaje_error != "") {
            return $this->mensaje_error;
        } else {
            throw new ExcepcionAccion("No reconozco esa instruccion");
        }
    }
}