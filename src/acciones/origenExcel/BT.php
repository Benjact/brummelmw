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
            $fase = $values[1][0];

            $array_pelotones = [];
            $filas = ["norte/naves" => 16, "centro" => 34, "sur" => 52];
            $columnas = [4, 8, 12, 16, 20, 24];

            foreach ($filas as $territorio => $fila) {
                $muestra_territorio = $this->muestraTerritorio($fase, $territorio);
                if ($muestra_territorio) {
                    $array_pelotones[] = "----" . BOLD . mb_strtoupper($territorio) . BOLD_CERRAR . "----";
                    foreach ($columnas as $n_peloton => $columna) {
                        $pelotonSINO = $this->pelotonRellenable($values, $fila, $columna);
                        $array_pelotones[] = BOLD . "PELOTON " . ($n_peloton + 1) . ": " . BOLD_CERRAR . $pelotonSINO;
                    }
                    $array_pelotones[] = "";
                }
            }
            return $array_pelotones;
        }
    }

    protected function maquetarValores($valores_comprobados)
    {
        return implode(ENTER, (array)$valores_comprobados);
    }

    /**
     * @param array $values
     * @param $fila
     * @param $columna
     * @return string
     */
    protected function pelotonRellenable(array $values, $fila, $columna): string
    {
        $pelotonSINO = "SI";
        if (isset($values[$fila - 1][$columna]) && mb_strtoupper($values[$fila - 1][$columna]) == "SKIP") {
            $pelotonSINO = "\xF0\x9F\x9A\xAB";
        } elseif (isset($values[$fila][$columna]) && $values[$fila][$columna] != "") {
            $pelotonSINO = "\xF0\x9F\x9A\xAB";
        }
        return $pelotonSINO;
    }

    protected function muestraTerritorio($fase, $territorio): bool
    {
        if (in_array($fase, [1, 2]) && $territorio == "norte/naves") return false;
        return true;
    }
}