<?php
namespace BrummelMW\core;

interface iBot
{
    public function chatId(): string;

    public function chatType(): string;

    public function mensaje(): string;

    /**
     * @return string
     */
    public function webSite(): string;
}