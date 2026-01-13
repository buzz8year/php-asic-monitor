<?php
/**
 * Energy consumption routes
 */

namespace App\EnergyConsumption;

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
        if (preg_match("#^/?EnergyConsumption/?$#", $this->path)) {
            // Page for creating invoices for total energy consumption
            return $this->controller_entity = (new App\EnergyConsumption\CallableControllers\Index(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\EnergyConsumption\Views\Html\EnergyConsumptionView()
            ))->index();
        }

        // if (preg_match("#^/?Api/EnergyConsumption/?$#", $this->path)) {
        //     return $this->controller_entity = (new App\EnergyConsumption\CallableControllers\Index(
        //         $_REQUEST,
        //         new App\Layouts\Json\DefaultJson(),
        //         new App\EnergyConsumption\Views\Json\EnergyConsumptionView()
        //     ))->new();
        // }

        if (preg_match("#^/?EnergyConsumption/Invoice/([0-9]+)/?$#", $this->path, $match)) {
            // Particular invoice detail view
            return $this->controller_entity = (new App\EnergyConsumption\CallableControllers\Index(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\EnergyConsumption\Views\Html\InvoiceView()
            ))->invoice($match[1]);
        }

        if (preg_match("#^/?EnergyConsumption/NewInvoice/?$#", $this->path)) {
            // Display new invoice
            return $this->controller_entity = (new App\EnergyConsumption\CallableControllers\Index(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\EnergyConsumption\Views\Html\NewInvoiceView()
            ))->newInvoice();
        }

        if (preg_match("#^/?EnergyConsumption/Invoice/Edit/([0-9]+)/Save/?$#", $this->path, $match)) {
            return $this->controller_entity = (new App\EnergyConsumption\CallableControllers\Index(
                $_REQUEST,
                new App\Layouts\Html\DashboardHtml(),
                new App\EnergyConsumption\Views\Html\InvoiceView()
            ))->save($match[1]);
        }
        
        return false;
    }
}
