<?php
namespace BrummelMW\response;

use BrummelMW\bot\iBot;

class Response
{
    /**
     * @var iBot
     */
    private $bot;

    public function __construct(iBot $bot)
    {
        $this->bot = $bot;
    }

    public function devolverMensaje(ObjetoResponse $objetoResponse)
    {
        $url = $this->bot->webSite()."/".$objetoResponse->tipoRespuesta();

        $array_respuesta = $objetoResponse->arrayPost();

        $ch = curl_init();
        //  set the url
        curl_setopt($ch, CURLOPT_URL, $url);
        //  number of POST vars
        curl_setopt($ch, CURLOPT_POST, count($array_respuesta));
        //  POST data
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($array_respuesta));
        //  To display result of curl
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //  execute post
        $result = curl_exec($ch);
        if (!$result) {
            trigger_error(curl_error($ch));
        }

        curl_close($ch);
    }
}