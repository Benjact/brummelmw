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

    public function devolverMensaje($mensaje)
    {
        $url = $this->bot->webSite()."/sendMessage";

        $fields = [
            "chat_id" => $this->bot->chatId(),
            "parse_mode" => PARSE_MODE,
            "text" => is_array($mensaje) ? implode("\\", $mensaje) : $mensaje,
        ];
        echo "entra aqui<pre>".print_r($fields, true)."</pre>";
        $ch = curl_init();
        //  set the url
        curl_setopt($ch, CURLOPT_URL, $url);
        //  number of POST vars
        curl_setopt($ch, CURLOPT_POST, count($fields));
        //  POST data
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
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