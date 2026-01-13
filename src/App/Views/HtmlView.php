<?php


namespace App\Views;


class HtmlView extends BaseView implements ViewInterface
{
    /**
     * @inheritdoc
     */
    public function out()
    {
        if (!headers_sent()) {
            header("Content-type: text/html; encode=\"UTF-8\"");
        }

        parent::out();
    }
}