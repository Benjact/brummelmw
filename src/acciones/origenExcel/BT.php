<?php
namespace BrummelMW\acciones\origenExcel;

use BrummelMW\acciones\AccionBasica;
use BrummelMW\acciones\iAcciones;
use BrummelMW\response\ObjetoResponse;
use Exception;
use Google_Client;
use Google_Service_Sheets;

class BT extends Excel
{
    const ID = "1L5T8Zso07c5wNKPnW0werZnQ5zS8FAdtD0GWliqg76s";
    const RANGO = "Platoon!A1:Y53";
}