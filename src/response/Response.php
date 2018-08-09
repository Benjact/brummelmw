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

    public function devolver_mensaje(string $mensaje)
    {
        $url = $this->bot->webSite()."/sendMessage?chat_id={$this->bot->chatId()}&parse_mode=HTML&text=".urlencode($mensaje);
        file_get_contents($url);
    }
}