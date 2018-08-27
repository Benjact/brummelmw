<?php
namespace BrummelMW\acciones;

use BrummelMW\response\ObjetoResponse;

interface iAcciones
{
    /**
     * iAcciones constructor.
     * @param string $parametro
     * @param array $objetoJSON
     */
    public function __construct(string $parametro = "", array $objetoJSON = []);

    /**
     * @return ObjetoResponse
     */
    public function retorno(): ObjetoResponse;
}