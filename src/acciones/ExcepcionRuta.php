<?php
namespace BrummelMW\acciones;

use Exception;
use phpDocumentor\Reflection\Types\Parent_;
use Throwable;

class ExcepcionRuta extends Exception
{
    public function __construct($ruta = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct("La ruta {$ruta} no está disponible", $code, $previous);
    }
}