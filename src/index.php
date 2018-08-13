<?php
namespace BrummelMW;

require_once __DIR__ . '/core/ini.php';
require __DIR__ . '/../vendor/autoload.php';

use BrummelMW\acciones\AccionesGeneral;
use BrummelMW\core\Bot;
use BrummelMW\core\BotHTML;
use BrummelMW\response\Response;
use BrummelMW\response\ResponseHTML;

$update = json_decode(file_get_contents("php://input"), true);
if (is_null($update)) {

    $update = [];
    $update["message"]["chat"]["id"] = "411317956";
    $update["message"]["chat"]["type"] = "private";
    $update["message"]["text"] = "/ayuda";

    $bot = new Bot(TOKEN, RUTA_API, $update);
    $response = new Response($bot);
    $response->devolverMensaje(print_r($update, true));

    /*if (!isset($_GET["instruccion"])) $_GET["instruccion"] = "";

    $bot = new BotHTML("/" . $_GET["instruccion"]);
    $response = new ResponseHTML();*/
} else {
    $bot = new Bot(TOKEN, RUTA_API, $update);
    $response = new Response($bot);
}

$acciones = new AccionesGeneral($bot->mensaje());
$response->devolverMensaje($acciones->retorno());