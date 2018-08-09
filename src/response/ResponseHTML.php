<?php
namespace BrummelMW\response;

class ResponseHTML
{
    public function __construct()
    {
    }

    public function devolverMensaje(string $mensaje)
    {
        echo $mensaje;
    }
}