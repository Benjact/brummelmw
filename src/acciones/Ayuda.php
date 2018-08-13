<?php
namespace BrummelMW\acciones;

class Ayuda extends AccionBasica implements iAcciones
{
    public function retorno(): string
    {
        return "Estoy en ello";
    }
}