<?php
namespace BrummelMW\acciones\swgoh;

trait TraitHoth
{
    protected function buscarCandidatos(array $datos_personaje, int $estrellas = 1)
    {
        $recopilacion = [];
        foreach ($datos_personaje as $jugador) {
            if ($jugador["rarity"] >= $estrellas) {
                $recopilacion[$jugador["player"]] = $jugador["power"];
            }
        }

        asort($recopilacion);

        foreach ($recopilacion as $jugador_nombre => $pg) {
            $datos_retorno[] = $jugador_nombre." ".$pg;
        }
        return $datos_retorno;
    }
}