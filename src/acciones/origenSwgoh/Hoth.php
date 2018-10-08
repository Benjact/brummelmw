<?php
namespace BrummelMW\acciones\origenSwgoh;

class Hoth extends Personajes
{
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
        return "Personaje {$this->personaje} no encontrado par Hoth";
    }
}