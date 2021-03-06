<?php

namespace BrummelMW;

require_once __DIR__ . '/core/ini.php';
require __DIR__ . '/../vendor/autoload.php';

use BrummelMW\acciones\AccionesGeneral;
use BrummelMW\acciones\ExcepcionAccion;
use BrummelMW\bot\Bot;
use BrummelMW\bot\BotHTML;
use BrummelMW\response\ObjetoResponse;
use BrummelMW\response\Response;
use BrummelMW\response\ResponseHTML;

$update = json_decode(file_get_contents("php://input"), true);
$inline = false;
if (is_null($update)) {
    if (!isset($_GET["instruccion"])) $_GET["instruccion"] = "";

    if (isset($_GET["debug"]) && $_GET["debug"]) {
        $update = [];
        $update["message"]["chat"]["id"] = "411317956";
        $update["message"]["chat"]["type"] = "private";
        $update["message"]["text"] = $_GET["instruccion"];
        $update["message"]["from"]["username"] = "Amthorn";

        $bot = new Bot(TOKEN, RUTA_API, $update);
        $response = new Response($bot);
    } else {
        //https://brumelmw.000webhostapp.com/src/index.php?debug=0&instruccion=
        $bot = new BotHTML("/" . $_GET["instruccion"]);
        $response = new ResponseHTML();
    }
} else {
    $bot = new Bot(TOKEN, RUTA_API, $update);

    $response = new Response($bot);
}

if (in_array($bot->username(), USUARIOS_PERMITIDOS)) {
    try {
        $acciones = new AccionesGeneral($bot, $inline);
        $response->devolverMensaje($acciones->retorno());
    } catch (ExcepcionAccion $e) {
        $response->devolverMensaje(new ObjetoResponse(ObjetoResponse::MENSAJE, [
            "chat_id" => $bot->chatId(),
            "parse_mode" => PARSE_MODE,
            "text" => $e->getMessage(),
        ]));
    }
} else {
    $mensajes = [
        "...",
        "dadme vaciones",
        "paso de vosotros",
        "mi primo el de lego lo sabe mejor",
        "os odio... a todos... a nani más...",
    ];
    $response->devolverMensaje(new ObjetoResponse(ObjetoResponse::MENSAJE, [
        "chat_id" => $bot->chatId(),
        "parse_mode" => PARSE_MODE,
        "text" => array_rand($mensajes),
    ]));
}
