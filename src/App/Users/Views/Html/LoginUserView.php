<?php

namespace App\Users\Views\Html;


use App\Bootstrap3\Helpers\ResultError;use App\Views\HtmlView;
use App\Views\ViewInterface;

class LoginUserView extends HtmlView implements ViewInterface
{
    /**
     * @var string
     */
    protected $login = '';

    /**
     * @see login
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @see login
     * @param string $login
     * @return LoginUserView
     */
    public function setLogin(string $login): LoginUserView
    {
        $this->login = $login;
        return $this;
    }



    /**
     * @inheritdoc
     */
    public function out(): void
    {
       ?>
        <div class="row" style="margin-top: 50px">
            <form action="/Users/Login/Do" method="POST" data-validate="form" role="form" id="signin-form" autocomplete="off">

                <h3>Login</h3>

                <?php (new ResultError($this->getResult()))->out(); ?>

                <div class="form-group">
                    <div class="input-group input-group-in">
                        <span class="input-group-addon text-muted">@</span>
                        <input type="text" class="form-control" name="login" required placeholder="Login" autocomplete="off">
                    </div><!--/input-group-->
                </div><!--/form-group-->

                <div class="form-group">
                    <div class="input-group input-group-in">
                        <span class="input-group-addon text-muted"><i class="fa fa-circle-o"></i></span>
                        <input type="password" class="form-control" name="password" required placeholder="Password" autocomplete="new-password">
                    </div><!--/input-group-->
                </div><!--/form-group-->

                <div class="form-group form-actions">
                    <input type="submit" class="hidden-sm btn btn-primary" value="Login">
                    <input type="submit" class="visible-sm btn btn-lg btn-block btn-primary" value="Login">
                </div><!--/form-group-->
            </form>
        </div>
        <?php
    }
}