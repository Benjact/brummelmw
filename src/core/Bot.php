<?php
namespace BrummelMW\core;

class Bot implements iBot
{
    protected $botToken = "";
    protected $webSite = "";
    protected $update = [];

    public function __construct(string $botToken, string $webSite, array $update)
    {
        $this->botToken = $botToken;
        $this->webSite = $webSite;
        $this->update = $update;
    }

    public function chatId(): string
    {
        return $this->update["message"]["chat"]["id"];
    }

    public function chatType(): string
    {
        return $this->update["message"]["chat"]["type"];
    }

    public function mensaje(): string
    {
        return $this->update["message"]["text"];
    }

    public function username(): string
    {
        return $this->update["from"]["username"];
    }

    /**
     * @return string
     */
    public function webSite(): string
    {
        return $this->webSite.$this->botToken;
    }
}