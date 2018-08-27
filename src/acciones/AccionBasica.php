<?php
namespace BrummelMW\acciones;

use BrummelMW\response\ObjetoResponse;

class AccionBasica implements iAcciones
{
    public function __construct(string $parametro = "", array $objetoJSON = [])
    {
    }

    /**
     * @param string $id_chat
     * @return ObjetoResponse
     * @throws ExcepcionAccion
     */
    public function retorno(string $id_chat): ObjetoResponse
    {
        throw new ExcepcionAccion("Esta instrucción no tiene implementado el retorno");
    }
}