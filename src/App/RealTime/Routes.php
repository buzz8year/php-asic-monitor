<?php

namespace App\RealTime;

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
        if (preg_match("#^/?(RealTime|\\/)/?$#", $this->path)) {
            return $this->controller_entity = (new App\RealTime\CallableControllers\Index(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\RealTime\Views\Html\IndexView()
            ))->index();
        }

        if (preg_match("#^/?RealTime/UnitsDetails/?$#", $this->path)) {
            return $this->controller_entity = (new App\RealTime\CallableControllers\Index(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\RealTime\Views\Html\UnitsDetailsView()
            ))->unitsDetails();
        }

        if (preg_match("#^/?RealTime/FullData/([0-9]+)/?$#", $this->path, $match)) {
            return $this->controller_entity = (new App\RealTime\CallableControllers\Index(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\RealTime\Views\Html\FullDataView()
            ))->fullData($match[1]);
        }

        if (preg_match("#^/?RealTime/Flow/?$#", $this->path)) {
            return $this->controller_entity = (new App\RealTime\CallableControllers\Index(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\RealTime\Views\Html\FlowView()
            ))->flow();
        }

        if (preg_match("#^/?Api/RealTime/Flow/?$#", $this->path)) {
            return $this->controller_entity = (new App\RealTime\CallableControllers\Index(
                $_REQUEST,
                new App\Layouts\Json\DefaultJson(),
                new App\RealTime\Views\Json\FlowView()
            ))->flow();
        }

        return false;
    }
}