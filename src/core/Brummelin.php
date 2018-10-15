<?php
namespace BrummelMW\core;

use Telegram\Bot\Api;

class Brummelin
{
    protected $update;
    protected $debug;
    protected $inline;
    protected $apiTelegram;

    public function __construct(string $token, array $update = null, bool $debug = false)
    {
        $this->debug = $debug;
        $this->update = $this->iniciarUpdate($update);
        $this->inline = $this->comprobarInline();
        $this->apiTelegram = $this->inicarBot($token);
    }

    private function iniciarUpdate(array $update = null)
    {
        if ($update == null && $this->debug) {
            $update = [];
            $update["message"]["chat"]["id"] = "411317956";
            $update["message"]["chat"]["type"] = "private";
            $update["message"]["text"] = $_GET["instruccion"] ?? "";
            $update["message"]["from"]["username"] = "Amthorn";
        }

        return $update;
    }

    private function comprobarInline()
    {
        if (isset($this->update["query"])) return true;
        return false;
    }

    public function __get(string $valor)
    {
        if (isset($this->$valor)) {
            return $this->$valor;
        }
        return null;
    }

    public function inicarBot(string $token)
    {
        return new Api($token, true);
    }
}