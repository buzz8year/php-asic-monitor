<?php

namespace App\Layouts\Html;

use App\Layouts\BaseLayout;
use App\Layouts\LayoutInterface;
use App\Views\ViewInterface;

class DefaultHtml extends BaseLayout implements LayoutInterface, ViewInterface
{
    /**
     * @inheritdoc
     */
    public function out()
    {
        if (!headers_sent()) {
            header("Content-Type: {$this->getContentMimeType()}; charset=utf-8");
        }

        ?>
        
        <html>
            <head>
                <title><?php print $this->getWindowTitle();?></title>
            </head>
            <body>
                <h1><?php print $this->getHeaderTitle();?></h1>
            <div>
                <?php print $this->getContent();?>
            </div>
            </body>
        </html>

        <?php
    }
}