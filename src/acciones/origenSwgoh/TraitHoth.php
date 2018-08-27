<?php
namespace BrummelMW\acciones\origenSwgoh;

trait TraitHoth
{
    protected $cantidad_retorno = 5;

    /**
     * @param int $cantidad_retorno
     */
    public function setCantidadRetorno(int $cantidad_retorno)
    {
        $this->cantidad_retorno = $cantidad_retorno;
    }

    protected function buscarCandidatos(array $datos_personaje, int $estrellas = 1)
    {
        $recopilacion = [];
        foreach ($datos_personaje as $jugador) {
            if ($jugador["rarity"] >= $estrellas) {
                $recopilacion[$jugador["player"]] = $jugador["power"];
            }
        }

        asort($recopilacion);
        foreach (array_slice($recopilacion, 0, $this->cantidad_retorno) as $jugador_nombre => $pg) {
            $datos_retorno[] = BOLD.$jugador_nombre.BOLD_CERRAR." PG: ".$pg;
        }
        return $datos_retorno;
    }
}