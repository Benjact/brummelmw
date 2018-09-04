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
            $array_pelotones = [];
            $filas = ["norte" => 16, "centro" => 34, "sur" => 52];
            $columnas = [4, 8, 12, 16, 20, 24];
            foreach ($filas as $posicion_mapa => $fila) {
                $array_pelotones[] = $posicion_mapa;
                foreach ($columnas as $n_peloton => $columna) {
                    $array_pelotones[] = "PELOTON {$n_peloton} :".($values[$fila][$columna] == "" ? "SI" : "NO");
                }
            }
            return $array_pelotones;
        }
    }
}