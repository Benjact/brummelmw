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
     * @param string $id_chat
     * @return ObjetoResponse
     */
    public function retorno(string $id_chat): ObjetoResponse;
}