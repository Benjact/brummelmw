<?php
namespace BrummelMW\response;

use BrummelMW\core\Bot;

class Response
{
    /**
     * @var Bot
     */
    private $bot;

    public function __construct(Bot $bot)
    {
        $this->bot = $bot;
    }

    public function devolverMensaje(string $mensaje)
    {
        $url = $this->bot->webSite()."/sendMessage";
        // following ones are optional, so could be set as null
        $disable_web_page_preview = null;
        $reply_to_message_id = null;
        $reply_markup = null;

        $fields = [
            "chat_id" => $this->bot->chatId(),
            "parse_mode" => "HTML",
            "text" => urlencode($mensaje),
            'disable_web_page_preview' => urlencode($disable_web_page_preview),
            'reply_to_message_id' => urlencode($reply_to_message_id),
            'reply_markup' => urlencode($reply_markup),
        ];

        $ch = curl_init();
        //  set the url
        curl_setopt($ch, CURLOPT_URL, $url);
        //  number of POST vars
        curl_setopt($ch, CURLOPT_POST, count($fields));
        //  POST data
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
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