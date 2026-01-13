<?php

namespace App\Bootstrap3\Helpers;


use App\Views\BaseView;
use App\Views\ViewInterface;

/**
 * Class ResultError
 * @package App\Bootstrap3\Helpers
 */
class ResultError extends BaseView implements ViewInterface
{

    /**
     * ResultError constructor.
     * @param \App\Result $result
     */
    public function __construct(\App\Result $result)
    {
        $this->result = $result;
        parent::__construct();
    }

    /**
     * @return void
     */
    public function out()
    {
        ?>

        <?php if (!$this->getResult()->isSuccess()):?>
        <div class="callout callout-danger">
            <h3>Error</h3>
            <?php foreach($this->getResult()->getErrors() as $error):?>
                <p><?php print $error;?></p>
            <?php endforeach;?>
        </div>
        <?php endif;?>

        <?php
    }
}