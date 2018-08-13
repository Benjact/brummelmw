<?php
namespace BrummelMW\acciones;

interface iAcciones
{
    public function __construct(string $parametro = "");
    public function retorno(): string;
}