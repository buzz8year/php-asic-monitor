<?php
/**
 * Контроллер статуса
 */

namespace App\Locations;

use App;
use App\Front\CallableControllerInterface;
use App\Front\Dispatcher;

class Routes extends Dispatcher
{
    /**
     * @inheritdoc
     * @return CallableControllerInterface|bool
     */
    public function dispatch()
    {
        if (preg_match("#^/?Locations/?$#", $this->path)) {
            // Главная страница состония оборудования
            return $this->controller_entity = (new App\Locations\CallableControllers\Index(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\Locations\Views\Html\IndexHtmlView()
            ))->index();
        }

		if(preg_match("#^/Api/Locations/List/?$#", $this->path)) {
			//Retrieval of locations for desktop app
			return $this->controller_entity = (new App\Locations\CallableControllers\Index(
                $_REQUEST,
                new App\Layouts\Json\DefaultJson(),
                new App\Locations\Views\Json\ListJsonView()
            ))->listLocations();
		}

        return false;
    }
}