<?php

namespace App\Bootstrap3\Helpers;


use App\Views\BaseView;
use App\Views\ViewInterface;

/**
 * Class Result
 * @package App\Bootstrap3\Helpers
 */
class Result extends BaseView implements ViewInterface
{
    /**
     * @var string
     */
    protected $success_return_url;
    /**
     * @var string
     */
    protected $success_body = "Операция выполнена успешно.";

    /**
     * Result constructor.
     * @param \App\Result $result
     */
    public function __construct(\App\Result $result)
    {
        $this->result = $result;
        parent::__construct();
    }

    /**
     * Set success_return_url
     * @see success_return_url
     * @param string $success_return_url
     * @return Result
     */
    public function setSuccessReturnUrl($success_return_url)
    {
        $this->success_return_url = $success_return_url;
        return $this;
    }

    /**
     * Set success_body
     * @see success_body
     * @param string $success_body
     * @return Result
     */
    public function setSuccessBody($success_body)
    {
        $this->success_body = $success_body;
        return $this;
    }

    /**
     * @return void
     */
    public function out()
    {
        ?>

        <?php if ($this->getResult()->isSuccess()):?>
            <div class="callout callout-success">
                <h4><i class="fa fa-check-circle"></i> Успешно</h4>
                <p><?php print $this->success_body;?></p>
            </div>
            <?php if ($this->success_return_url):?>
                <a class="btn btn-default" href="<?php print $this->success_return_url;?>">Перейти на следующую страницу</a>
            <?php endif;?>
        <?php else:;?>
            <div class="callout callout-danger">
                <h3>Error`</h3>
                <?php foreach($this->getResult()->getErrors() as $error):?>
                    <p><?php print $error;?></p>
                <?php endforeach;?>
            </div>
        <?php endif;?>
        <?php
    }
}