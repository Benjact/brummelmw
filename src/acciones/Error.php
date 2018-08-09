<?php
namespace BrummelMW\acciones;

class Error implements iAcciones
{
    public function __construct()
    {
    }

    public function retorno(): string
    {
        return "No reconozco esa instruccion";
    }
}