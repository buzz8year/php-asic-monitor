<?php

namespace App\Layouts\Json;

use App\Layouts\BaseLayout;
use App\Layouts\LayoutInterface;
use App\Views\ViewInterface;

class DefaultJson extends BaseLayout implements LayoutInterface, ViewInterface, \JsonSerializable
{

    /**
     * @inheritdoc
     */
    function jsonSerialize()
    {
        return array(
            "response_code" => $this->getResponseCode(),
            "location_redirect_uri" => $this->getLocationRedirectUri(),
            "title" => $this->getWindowTitle() ? $this->getWindowTitle() : $this->getHeaderTitle(),
            "window_title" => $this->getWindowTitle(),
            "header_title" => $this->getHeaderTitle(),
            "content" => $this->getContent()
        );
    }

    /**
     * @inheritdoc
     */
    public function out()
    {
        if (!headers_sent()) {
            header("Content-Type: text/json; charset=UTF-8");
        }

        exit(json_encode($this));
    }



}