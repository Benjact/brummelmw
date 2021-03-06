<?php
namespace BrummelMW\acciones;

use BrummelMW\response\ObjetoResponse;

class AccionBasica implements iAcciones
{
    /**
     * @var string
     */
    protected $parametro;
    /**
     * @var array
     */
    protected $objetoJSON;

    public function __construct(string $parametro = "", array $objetoJSON = [])
    {
        $this->parametro = $parametro;
        $this->objetoJSON = $objetoJSON;
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