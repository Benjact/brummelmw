<?php
namespace BrummelMW\acciones\swgoh;

use BrummelMW\acciones\AccionBasica;
use BrummelMW\acciones\ExcepcionAccion;

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

    protected function recuperar_json(array $recuperar_json): array
    {
        return $recuperar_json;
    }

    /**
     * @return array|string
     * @throws ExcepcionAccion
     */
    public function retorno()
    {
        $total = 0;
        $total_personajes = 0;
        $total_naves = 0;
        foreach ($this->objetoJSON as $personaje) {
            foreach ($personaje as $jugador) {
                if ($jugador["combat_type"] == "1") {
                    $total_personajes += $jugador["power"];
                } else {
                    if ($jugador["player"] == "AMTHORN")
                        echo $jugador["power"]." = ".$total_naves."<br>";
                    $total_naves += $jugador["power"];
                }
                $total += $jugador["power"];
            }
        }

        return [
            BOLD."PG TOTAL:".BOLD_CERRAR." {$total}",
            BOLD."PG PERSONAJES:".BOLD_CERRAR." {$total_personajes}",
            BOLD."PG NAVES:".BOLD_CERRAR." {$total_naves}",
        ];
    }
}