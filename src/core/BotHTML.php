<?php
namespace BrummelMW\core;

class BotHTML implements iBot
{
    /**
     * @var string
     */
    private $mensaje;

    public function __construct(string $mensaje)
    {
        $this->mensaje = $mensaje;
    }

    public function chatId(): string
    {
        return "id";
    }

    public function chatType(): string
    {
        return "type";
    }

    public function mensaje(): string
    {
        return $this->mensaje;
    }

    public function username(): string
    {
        return "superamoweb";
    }

    /**
     * @return string
     */
    public function webSite(): string
    {
        return "webSite";
    }
}