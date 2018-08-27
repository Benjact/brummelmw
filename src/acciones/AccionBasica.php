<?php
namespace BrummelMW\acciones;

use BrummelMW\response\ObjetoResponse;

class AccionBasica implements iAcciones
{
    public function __construct(string $parametro = "", array $objetoJSON = [])
    {
    }

    /**
     * @return ObjetoResponse
     * @throws ExcepcionAccion
     */
    public function retorno(): ObjetoResponse
    {
        throw new ExcepcionAccion("Esta instrucción no tiene implementado el retorno");
    }
}