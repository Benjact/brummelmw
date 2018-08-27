<?php
namespace BrummelMW\acciones\origenSwgoh;

use BrummelMW\acciones\AccionBasica;
use BrummelMW\acciones\ExcepcionAccion;
use BrummelMW\response\ObjetoResponse;

class Gremio extends AccionBasica
{
    /**
     * @var array
     */
    private $objetoJSON;

    public function __construct(string $parametro = "", array $objetoJSON = [])
    {
        $this->objetoJSON = $this->recuperar_json($objetoJSON);
    }

    /**
     * @param array $recuperar_json
     * @return array
     */
    protected function recuperar_json(array $recuperar_json): array
    {
        return $recuperar_json;
    }

    /**
     * @return ObjetoResponse
     */
    public function retorno(): ObjetoResponse
    {
        $total = 0;
        $total_personajes = 0;
        $total_naves = 0;
        foreach ($this->objetoJSON as $nombre_personaje => $personaje) {
            foreach ($personaje as $jugador) {
                if ($jugador["combat_type"] == "1") {
                    $total_personajes += $jugador["power"];
                } else {
                    $total_naves += $jugador["power"];
                }
                $total += $jugador["power"];
            }
        }
        $array_mensaje = [
            BOLD."PG TOTAL:".BOLD_CERRAR." {$total}",
            BOLD."PG PERSONAJES:".BOLD_CERRAR." {$total_personajes}",
            BOLD."PG NAVES:".BOLD_CERRAR." {$total_naves}",
        ];
        return new ObjetoResponse(ObjetoResponse::MENSAJE, [
            "parse_mode" => PARSE_MODE,
            "text" => implode(ENTER, $array_mensaje),
        ]);
    }
}