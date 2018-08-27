<?php
namespace BrummelMW\bot;

interface iBot
{
    public function id(): string;

    public function chatId(): string;

    public function chatType(): string;

    public function mensaje(): string;

    public function username(): string;

    /**
     * @return string
     */
    public function webSite(): string;
}