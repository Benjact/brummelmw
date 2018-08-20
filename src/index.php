<?php
namespace BrummelMW;

require_once __DIR__ . '/core/ini.php';
require __DIR__ . '/../vendor/autoload.php';

use BrummelMW\acciones\AccionesGeneral;
use BrummelMW\acciones\ExcepcionAccion;
use BrummelMW\bot\Bot;
use BrummelMW\bot\BotInline;
use BrummelMW\bot\BotHTML;
use BrummelMW\response\Response;
use BrummelMW\response\ResponseHTML;
use BrummelMW\response\ResponseInline;

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
        echo "entra aqui";
        $response = new Response($bot);
    } else {
        $bot = new BotHTML("/" . $_GET["instruccion"]);
        $response = new ResponseHTML();
    }
} else {
    if (isset($update["message"]["chat"])) {
        $bot = new Bot(TOKEN, RUTA_API, $update);

        $response = new Response($bot);
    } else {
        $bot = new BotInline(TOKEN, RUTA_API, $update);
        $response = new ResponseInline($bot);
        $inline = true;
    }
}

try {
    $acciones = new AccionesGeneral($bot->mensaje(), $bot->username(), $inline);
    $response->devolverMensaje($acciones->retorno());
} catch (ExcepcionAccion $e) {
    $response->devolverMensaje($e->getMessage());
}