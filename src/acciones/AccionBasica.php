<?php
namespace BrummelMW\acciones;

class AccionBasica implements iAcciones
{
    public function __construct(string $parametro = "")
    {
    }

    public function retorno(): string
    {
        return "";
    }
}