<?php
namespace BrummelMW\acciones;

use BrummelMW\response\ObjetoResponse;

class AccionCompuesta implements iAcciones
{
    /**
     * @var string
     */
    protected $parametro;
    /**
     * @var array
     */
    protected $objetoJSON;
    /**
     * @var array
     */
    protected $objetoJSONextra;

    public function __construct(string $parametro = "", array $objetoJSON = [], array $objetoJSONextra = [])
    {
        $this->parametro = $parametro;
        $this->objetoJSON = $objetoJSON;
        $this->objetoJSONextra = $objetoJSONextra;
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