<?php
namespace BrummelMW\acciones;

use BrummelMW\response\ObjetoResponse;

class Error extends AccionBasica implements iAcciones
{
    /**
     * @param string $id_chat
     * @return ObjetoResponse
     * @throws ExcepcionAccion
     */
    public function retorno(string $id_chat): ObjetoResponse
    {
        if ($this->parametro != "") {
            return new ObjetoResponse(ObjetoResponse::MENSAJE, [
                "chat_id" => $id_chat,
                "parse_mode" => PARSE_MODE,
                "text" => $this->parametro,
            ]);
        } else {
            throw new ExcepcionAccion("No reconozco esa instruccion");
        }
    }
}