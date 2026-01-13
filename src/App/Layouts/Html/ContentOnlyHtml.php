<?php

namespace App\Layouts\Html;

use App\Layouts\BaseLayout;
use App\Layouts\LayoutInterface;
use App\Views\ViewInterface;

class ContentOnlyHtml extends BaseLayout implements LayoutInterface, ViewInterface
{
    /**
     * @inheritdoc
     */
    public function out()
    {
        if (!headers_sent()) {
            header("Content-Type: {$this->getContentMimeType()}; charset=utf-8");
        }

        print $this->content;
    }
}