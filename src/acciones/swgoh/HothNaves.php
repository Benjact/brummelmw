<?php
namespace BrummelMW\acciones\swgoh;

use BrummelMW\acciones\ExcepcionAccion;

class HothNaves extends Naves
{
    use TraitHoth;

    protected function infoPersonajeEstrellas(array $datos_personaje, int $estrellas = 1)
    {
        return $this->buscarCandidatos();
    }
}