<?php
namespace BrummelMW\acciones\origenSwgoh;

use BrummelMW\acciones\AccionBasica;
use BrummelMW\acciones\ExcepcionAccion;
use BrummelMW\response\ObjetoResponse;

class Template extends AccionBasica
{
    public function __construct(string $parametro = "", array $objetoJSON = [])
    {
        parent::__construct($parametro, $objetoJSON);

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
     * @param string $id_chat
     * @return ObjetoResponse
     */
    public function retorno(string $id_chat): ObjetoResponse
    {
        print_r($this->objetoJSON);

        return new ObjetoResponse(ObjetoResponse::MENSAJE, [
            "chat_id" => $id_chat,
            "parse_mode" => PARSE_MODE,
            "text" => "",
        ]);
    }
}