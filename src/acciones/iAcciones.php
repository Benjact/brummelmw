<?php
namespace BrummelMW\acciones;

interface iAcciones
{
    /**
     * iAcciones constructor.
     * @param string $parametro
     * @param array $objetoJSON
     */
    public function __construct(string $parametro = "", array $objetoJSON = []);

    /**
     * @return array|string
     * @throws ExcepcionAccion
     */
    public function retorno();
}