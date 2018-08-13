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

    public function retorno(): string
    {
        if ($this->parametro == "personajes") {
            return "Personajes posibles:<br>" . implode("<br>", (new Personajes())->personajes());
        } else {
            return "Estoy en ello";
        }
    }
}