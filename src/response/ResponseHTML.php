<?php
namespace BrummelMW\response;

class ResponseHTML
{
    public function devolverMensaje($mensaje)
    {
        echo is_array($mensaje) ? implode(ENTER, $mensaje) : $mensaje;
    }
}