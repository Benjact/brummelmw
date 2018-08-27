<?php
namespace BrummelMW\acciones;

use BrummelMW\response\ObjetoResponse;

class Error extends AccionBasica implements iAcciones
{
    protected $mensaje_error = "";

    public function __construct(string $parametro = "", array $objetoJSON = [])
    {
        $this->mensaje_error = $parametro;
    }

    /**
     * @return ObjetoResponse
     * @throws ExcepcionAccion
     */
    public function retorno(): ObjetoResponse
    {
        if ($this->mensaje_error != "") {
            return new ObjetoResponse(ObjetoResponse::MENSAJE, [
                "parse_mode" => PARSE_MODE,
                "text" => $this->mensaje_error,
            ]);
        } else {
            throw new ExcepcionAccion("No reconozco esa instruccion");
        }
    }
}