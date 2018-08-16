<?php
namespace BrummelMW\acciones\swgoh;

class Naves extends Personajes
{
    protected $combat_type = 2;

    /**
     * @return string
     */
    protected function avisoPersonajeNoEncontrado(): string
    {
        return "Nave {$this->personaje} no encontrada";
    }

    public function naves()
    {
        return $this->personajes();
    }
}