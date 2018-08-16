<?php
namespace BrummelMW\acciones;

class AccionBasica implements iAcciones
{
    public function __construct(string $parametro = "", array $objetoJSON = [])
    {
    }

    /**
     * @return array|string
     * @throws ExcepcionAccion
     */
    public function retorno()
    {
        return "";
    }
}