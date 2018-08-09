<?php
namespace BrummelMW\acciones;

class Ayuda implements iAcciones
{
    public function __construct()
    {
    }

    public function retorno(): string
    {
        return "Estoy en ello";
    }
}