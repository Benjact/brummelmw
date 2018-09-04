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
    protected $excel_id = "1L5T8Zso07c5wNKPnW0werZnQ5zS8FAdtD0GWliqg76s";

    /**
     * @return string
     */
    protected function rango(): string
    {
        return 'Platoon!A1:Y';
    }

    /**
     * @param $values
     * @return array|string
     */
    protected function comprobarValores(array $values)
    {
        if (empty($values)) {
            return parent::comprobarValores($values);
        } else {
            return $values;
        }
    }
}