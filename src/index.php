<?php
namespace BrummelMW;

require_once __DIR__ . '/core/ini.php';
require __DIR__ . '/../vendor/autoload.php';

use BrummelMW\acciones\AccionesGeneral;
use BrummelMW\core\Bot;
use BrummelMW\core\BotHTML;
use BrummelMW\response\Response;
use BrummelMW\response\ResponseHTML;

if ($_SERVER["HTTP_HOST"] == RUTA_WEB) {
    $update = json_decode(file_get_contents("php://input"), true);
    $bot = new Bot(TOKEN, RUTA_API, $update);
    $response = new Response($bot);
} else {
    $bot = new BotHTML("/".$_GET["instruccion"]);
    $response = new ResponseHTML();
}

$acciones = new AccionesGeneral($bot->mensaje());
$response->devolverMensaje($acciones->retorno());
echo "Hola mundo extra";
