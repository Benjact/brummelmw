<?php
namespace BrummelMW\response;

class ResponseHTML
{
    public function __construct()
    {
    }

    public function devolverMensaje(string $mensaje)
    {
        echo is_array($mensaje) ? implode("<br>", $mensaje) : $mensaje;
    }
}