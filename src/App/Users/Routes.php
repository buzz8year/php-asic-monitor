<?php

namespace App\Users;

use App;
use App\Front\CallableControllerInterface;
use App\Front\Dispatcher;

class Routes extends Dispatcher
{
    /**
     * @inheritdoc
     * @return CallableControllerInterface|bool
     * @throws App\HttpError4xxException
     */
    public function dispatch()
    {
        if (preg_match("#^/?Users/?$#", $this->path)) {
            return $this->controller_entity = (new App\Users\CallableControllers\Index(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\Users\Views\Html\ListUsersView()
            ))->index();
        }

        if (preg_match("#^/?Users/Add/?$#", $this->path)) {
            return $this->controller_entity = (new App\Users\CallableControllers\Add(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\Users\Views\Html\AddUserView()
            ))->index();
        }

        if (preg_match("#^/?Users/Add/Save/?$#", $this->path)) {
            return $this->controller_entity = (new App\Users\CallableControllers\Add(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\Users\Views\Html\AddUserView()
            ))->save();
        }

        if (preg_match("#^/?Users/Edit/([0-9]+)/?$#", $this->path, $match)) {
            return $this->controller_entity = (new App\Users\CallableControllers\Edit(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\Users\Views\Html\EditUserView()
            ))->index($match[1]);
        }

        if (preg_match("#^/?Users/Edit/([0-9]+)/Save/?$#", $this->path, $match)) {
            return $this->controller_entity = (new App\Users\CallableControllers\Edit(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\Users\Views\Html\EditUserView()
            ))->save($match[1]);
        }

        if (preg_match("#^/?Users/Login/?$#", $this->path)) {
            return $this->controller_entity = (new App\Users\CallableControllers\Login(
                $_REQUEST,
                new App\Layouts\Html\DashboardLoginHtml(),
                new App\Users\Views\Html\LoginUserView()
            ))->index();
        }

        if (preg_match("#^/?Users/Login/Do?$#", $this->path)) {
            return $this->controller_entity = (new App\Users\CallableControllers\Login(
                $_REQUEST,
                new App\Layouts\Html\DashboardLoginHtml(),
                new App\Users\Views\Html\LoginUserView()
            ))->doLogin();
        }

        return false;
    }
}