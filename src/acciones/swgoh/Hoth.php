<?php
namespace BrummelMW\acciones\swgoh;

use BrummelMW\acciones\ExcepcionAccion;

class Hoth extends Personajes
{
    use TraitHoth;

    protected function infoPersonajeEstrellas(array $datos_personaje, int $estrellas = 1)
    {
        return $this->buscarCandidatos();
    }
}