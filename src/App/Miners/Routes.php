<?php
/**
 * Контроллер статуса
 */

namespace App\Miners;

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
        if (preg_match("#^/?(Miners)/?$#", $this->path)) {
            // Главная страница состония оборудования
            return $this->controller_entity = (new App\Miners\CallableControllers\Index(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\Miners\Views\Html\ListMinersView()
            ))->index();
        }

        if (preg_match("#^/?Miners/Inactive/?$#", $this->path)) {
            // Главная страница состония оборудования
            return $this->controller_entity = (new App\Miners\CallableControllers\Index(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\Miners\Views\Html\ListMinersView()
            ))->index(true);
        }

        if (preg_match("#^/?Miners/ConfiguredDevices/?$#i", $this->path)) {
            return $this->controller_entity = (new App\Miners\CallableControllers\ConfiguredDevices(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\Miners\Views\Html\ConfiguredDevices\IndexView()
            ))->index();
        }

        if (preg_match("#^/?Miners/ConfiguredDevices/Add/?$#i", $this->path)) {
            return $this->controller_entity = (new App\Miners\CallableControllers\ConfiguredDevices(
                $_REQUEST,
                new App\Layouts\Json\DefaultJson(),
                new App\Miners\Views\Json\AddConfiguredDeviceView()
            ))->add();
        }

        if (preg_match("#^/?Miners/ConfiguredDevices/View/([0-9]+)/?$#i", $this->path, $match)) {
            return $this->controller_entity = (new App\Miners\CallableControllers\ConfiguredDevices(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\Miners\Views\Html\ConfiguredDevices\ShowDeiceView()
            ))->view($match[1]);
        }

        if (preg_match("#^/?Miners/ConfiguredDevices/Delete/([0-9]+)/?$#i", $this->path, $match)) {
            return $this->controller_entity = (new App\Miners\CallableControllers\ConfiguredDevices(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\Miners\Views\Html\ConfiguredDevices\IndexView()
            ))->delete($match[1]);
        }

        if (preg_match("#^/?Miners/ConfiguredDevices/CreateFrom/([0-9]+)/?$#i", $this->path, $match)) {
            return $this->controller_entity = (new App\Miners\CallableControllers\ConfiguredDevices(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\Miners\Views\Html\ConfiguredDevices\CreateFromView()
            ))->createFrom($match[1]);
        }

        if (preg_match("#^/?Miners/ConfiguredDevices/EditFrom/([0-9]+)/([0-9]+)/?$#i", $this->path, $match)) {

            return $this->controller_entity = (new App\Miners\CallableControllers\ConfiguredDevices(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\Miners\Views\Html\ConfiguredDevices\EditFromView()
            ))->editFrom($match[1], $match[2]);
        }

        // Routing for displaying up to ALLOCATION
        if (preg_match("#^/?Miners/Location/([0-9]+)/?$#", $this->path, $match)) {
            return $this->controller_entity = (new App\Miners\CallableControllers\Index(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\Miners\Views\Html\LocationMinersView()
            ))->location($match[1]);
        }

        if (preg_match("#^/?Miners/Add/?$#", $this->path)) {
            // Форма редактирования оборудования
            return $this->controller_entity = (new App\Miners\CallableControllers\Add(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\Miners\Views\Html\AddMinerView()
            ))->index();
        }

        if (preg_match("#^/?Miners/Add/Save/?$#", $this->path)) {
            // Форма редактирования оборудования
            return $this->controller_entity = (new App\Miners\CallableControllers\Add(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\Miners\Views\Html\AddMinerView()
            ))->save();
        }

        if (preg_match("#^/?Api/Miners/Add/Save/?$#", $this->path)) {
            // Форма редактирования оборудования
            return $this->controller_entity = (new App\Miners\CallableControllers\Add(
                $_REQUEST,
                new App\Layouts\Json\DefaultJson(),
                new App\Miners\Views\Json\ManageMinerView()
            ))->save();
        }

        if (preg_match("#^/?Miners/Edit/([0-9]+)/?$#", $this->path, $match)) {
            // Форма редактирования оборудования
            return $this->controller_entity = (new App\Miners\CallableControllers\Edit(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\Miners\Views\Html\EditView()
            ))->index($match[1]);
        }

        if (preg_match("#^/?Miners/Edit/([0-9]+)/Save/?$#", $this->path, $match)) {
            // Форма редактирования оборудования
            return $this->controller_entity = (new App\Miners\CallableControllers\Edit(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\Miners\Views\Html\EditView()
            ))->save($match[1]);
        }

        if (preg_match("#^/?Api/Miners/RequestHostname/([0-9]+)/?$#", $this->path, $match)) {
            return $this->controller_entity = (new App\Miners\CallableControllers\Edit(
                $_REQUEST,
                new App\Layouts\Json\DefaultJson(),
                new App\Miners\Views\Json\RequestHostnameView()
            ))->requestHostname($match[1]);
        }

        return false;
    }
}