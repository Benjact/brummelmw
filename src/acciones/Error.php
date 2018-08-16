<?php
namespace BrummelMW\acciones;

class Error extends AccionBasica implements iAcciones
{
    protected $mensaje_error = "";

    public function __construct(string $parametro = "", array $objetoJSON = [])
    {
        $this->mensaje_error = $parametro;
    }

    /**
     * @return array|string
     * @throws ExcepcionAccion
     */
    public function retorno()
    {
        if ($this->mensaje_error != "") {
            return $this->mensaje_error;
        } else {
            throw new ExcepcionAccion("No reconozco esa instruccion");
        }
    }
}