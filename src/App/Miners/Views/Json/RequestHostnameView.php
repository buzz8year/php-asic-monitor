<?php

namespace App\Miners\Views\Json;

use App\Views\JsonView;
use App\Views\ViewInterface;

class RequestHostnameView extends JsonView implements ViewInterface, \JsonSerializable
{
    /**
     * Модель получени пока данных
     * @var response
     */
    protected $response;

    /**
     * @inheritDoc
     */
    public function getResponse() : array
    {
        return $this->response;
    }

    /**
     * @inheritDoc
     */
    public function setResponse(array $response) : RequestHostnameView
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return array(
            "result" => $this->getResult(),
            "response" => $this->getResponse(),
        );
    }

}