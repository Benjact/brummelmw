<?php
namespace BrummelMW\bot;

class BotInline implements iBot
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
        return $this->update["inline_query"]["id"];
    }

    public function chatId(): string
    {
        return $this->update["inline_query"]["from"]["id"];
    }

    public function chatType(): string
    {
        //return $this->update["inline_query"]["chat"]["type"];
        return "inline_query";
    }

    public function mensaje(): string
    {
        return $this->update["query"];
    }

    public function username(): string
    {
        return mb_strtolower($this->update["inline_query"]["from"]["username"]);
    }

    /**
     * @return string
     */
    public function webSite(): string
    {
        return $this->webSite.$this->botToken;
    }
}