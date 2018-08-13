<?php
namespace BrummelMW\response;

class ResponseHTML
{
    public function devolverMensaje($mensaje)
    {
        echo is_array($mensaje) ? implode("<br>", $mensaje) : $mensaje;
    }
}