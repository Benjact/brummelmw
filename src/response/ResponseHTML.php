<?php
namespace BrummelMW\response;

class ResponseHTML
{
    public function __construct()
    {
    }

    public function devolver_mensaje(string $mensaje)
    {
        echo $mensaje;
    }
}