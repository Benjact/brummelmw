<?php
namespace BrummelMW\bot;

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

    public function id(): string
    {
        return $this->update["message"]["message_id"];
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
        return mb_strtolower($this->update["message"]["from"]["username"]);
    }

    /**
     * @return string
     */
    public function webSite(): string
    {
        return $this->webSite.$this->botToken;
    }
}