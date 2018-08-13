<?php
namespace BrummelMW\acciones;

use BrummelMW\acciones\swgoh\Personajes;

class Ayuda extends AccionBasica implements iAcciones
{
    private $parametro = "";

    public function __construct(string $parametro = "")
    {
        $this->parametro = $parametro;
    }

    public function retorno()
    {
        if ($this->parametro == "personajes") {
            return (new Personajes())->personajes();
        } else {
            throw new ExcepcionAccion("Estoy en ello");
        }
    }
}