<?php
namespace BrummelMW\acciones;

interface iAcciones
{
    public function __construct(string $parametro = "");

    /**
     * @return array|string
     * @throws ExcepcionAccion
     */
    public function retorno();
}