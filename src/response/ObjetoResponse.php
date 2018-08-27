<?php
namespace BrummelMW\response;

class ObjetoResponse
{
    const MENSAJE = "sendMessage";

    /**
     * @var string
     */
    private $tipoRespuesta;
    /**
     * @var array
     */
    private $arrayPost;

    public function __construct(string $tipoRespuesta, array $arrayPost)
    {
        $this->tipoRespuesta = $tipoRespuesta;
        $this->arrayPost = $arrayPost;
    }

    /**
     * @return string
     */
    public function tipoRespuesta(): string
    {
        return $this->tipoRespuesta;
    }

    /**
     * @return array
     */
    public function arrayPost(): array
    {
        return $this->arrayPost;
    }
}