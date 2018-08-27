<?php
namespace BrummelMW\response;

class ResponseHTML
{
    public function devolverMensaje(ObjetoResponse $objetoResponse)
    {
        echo $objetoResponse->tipoRespuesta();

        echo "<pre>".print_r($objetoResponse->arrayPost(), true)."</pre>";
    }
}