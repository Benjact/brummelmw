<?php
namespace BrummelMW\acciones\origenSwgoh;

use BrummelMW\acciones\ExcepcionAccion;

class HothNaves extends Naves
{
    protected $combat_type = 2;

    use TraitHoth;

    protected function infoPersonajeEstrellas(array $datos_personaje, int $estrellas = 1)
    {
        return $this->buscarCandidatos($datos_personaje, $estrellas);
    }

    /**
     * @return string
     */
    protected function avisoPersonajeNoEncontrado(): string
    {
        return "Nave {$this->personaje} no encontrada para Hoth";
    }
}