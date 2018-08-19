<?php
namespace BrummelMW\bot;

interface iBot
{
    public function id(): string;

    public function chatId(): string;

    public function chatType(): string;

    public function mensaje(): string;

    /**
     * @return string
     */
    public function webSite(): string;
}