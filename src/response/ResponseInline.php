<?php
namespace BrummelMW\response;

use BrummelMW\bot\iBot;

class ResponseInline
{
    /**
     * @var iBot
     */
    private $bot;

    public function __construct(iBot $bot)
    {
        $this->bot = $bot;
    }

    public function devolverMensaje($resultados)
    {
        $url = $this->bot->webSite()."/answerInlineQuery";
        // following ones are optional, so could be set as null

        $fields = [
            "inline_query_id " => $this->bot->id(),
            "results" => json_encode((array)$resultados),
            "next_offset" => "",
        ];

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