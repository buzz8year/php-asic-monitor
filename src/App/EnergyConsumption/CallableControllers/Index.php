<?php

namespace App\EnergyConsumption\CallableControllers;

use App\BreadCrumbs;
use App\Db\PDOFactory;
use App\EnergyConsumption\GetEnergyInvoices4User;
use App\Front\CallableController;
use App\Front\CallableControllerInterface;
use App\Front\InternalCallableControllerException;
use App\EnergyInvoice;
use App\EnergyConsumption\Store;
use App\EnergyConsumption\GenerateEnergyInvoice;
use App\EnergyConsumption\Views\Html\EnergyConsumptionView;
use App\HttpError4xxException;
use App\Locations\GetLocations4User;
use App\UserAuth;
use App\UserRights;
use App\Views\ViewInterface;
use App\Strings;
use App\Models\GetAllModels;
use App\Datetime;


class Index extends CallableController implements CallableControllerInterface
{
    /**
     * @inheritDoc
     * @return EnergyConsumptionView|ViewInterface
     */
    public function getView()
    {
        return parent::getView();
    }

    /**
     * @return $this
     * @throws HttpError4xxException
     */
    public function index(): Index
    {

        if (!UserAuth::isUserAuthenticated()) {
            throw new HttpError4xxException("Unauthorized user", 403);
        }

        if (
            !UserAuth::getAuthenticatedUser()->hasAccess(UserRights::MANAGE_ENERGY_CONSUMPTION)
            && !UserAuth::getAuthenticatedUser()->hasAccess(UserRights::SEE_INVOICES)
        ) {
            throw new HttpError4xxException("You are can't access to this section", 403);
        }

        $this->getLayout()
            ->setWindowTitle('Energy Consumption & Invoices')
            ->setHeaderTitle('Energy Consumption & Invoices')
            ;

        $invoices = new GetEnergyInvoices4User(UserAuth::getAuthenticatedUser());
        $locations = new GetLocations4User(UserAuth::getAuthenticatedUser());
        $models = new GetAllModels();

        $this->getView()
            ->setInvoices($invoices->getInvoices(PDOFactory::getReadPDOInstance()))
            ->setLocations($locations->getLocations(PDOFactory::getReadPDOInstance()))
            ->setModels($models->getModels(PDOFactory::getReadPDOInstance()))
            ;

        return $this;
    }

    /**
     * @param $invoiceID
     * @return $this
     * @throws HttpError4xxException
     */
    public function invoice($invoiceID) : Index
    {
        try {

            if (!UserAuth::isUserAuthenticated()) {
                throw new HttpError4xxException("Unauthorized user", 403);
            }

            if (
                !UserAuth::getAuthenticatedUser()->hasAccess(UserRights::MANAGE_ENERGY_CONSUMPTION)
                && !UserAuth::getAuthenticatedUser()->hasAccess(UserRights::SEE_INVOICES)
            ) {
                throw new HttpError4xxException("You are can't access to this section", 403);
            }

            if (!is_numeric($invoiceID) || (int)$invoiceID <= 0) {
                throw new InternalCallableControllerException('Bad invoice id', 404);
            }

            $invoiceID = (int)$invoiceID; // fix type

            $invoice = EnergyInvoice::get($invoiceID);

            if (!$invoice->getId()) {
                throw new InternalCallableControllerException(sprintf("Invoice with id %u not found", $invoiceID));
            }

            if (!UserAuth::getAuthenticatedUser()->isLocationAllowed($invoice->getLocationId())) {
                throw new HttpError4xxException("You are can't access to this invoice, because location is deny", 403);
            }

            $this->getView()
                ->setInvoice($invoice);

            $this->getLayout()
                ->setWindowTitle('Invoice')
                ->setHeaderTitle('Invoice')
                ->addBreadCrumbs(new BreadCrumbs('Energy Consumption', '/EnergyConsumption'))
                ->addBreadCrumbs(new BreadCrumbs('Invoice Details'))
                ;


        } catch (InternalCallableControllerException $e) {
            $this->getView()->getResult()->addError($e->getMessage());
        }

        return $this;
    }

    /**
     * Controller for new invoice generating and further displaying
     * @return $this
     * @throws HttpError4xxException
     */
    public function newInvoice()
    {
        try {

            if (!UserAuth::isUserAuthenticated()) {
                throw new HttpError4xxException("Unauthorized user", 403);
            }

            if (!UserAuth::getAuthenticatedUser()->hasAccess(UserRights::MANAGE_ENERGY_CONSUMPTION)) {
                throw new HttpError4xxException("You are can't access to this section", 403);
            }

            $fromDate = 0;
            if ($this->getUserInputData('from_date')) {
                $fromDate = Datetime::create_force(Strings::trim($this->getUserInputData('from_date')))->getTimestamp();
            }

            $toDate = time();
            if ($this->getUserInputData('to_date')) {
                $toDate = Datetime::create_force(Strings::trim($this->getUserInputData('to_date')))->getTimestamp();
            }

            $locationID = 1;
            if (null !== $this->getUserInputData('location_id')) {
                $locationID = (int)$this->getUserInputData('location_id');
            }

            $details = false;
            if (null !== $this->getUserInputData('details')) {
                $details = (bool)$this->getUserInputData('details');
            }

            $data = [
                'location_id' => $locationID,
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'details' => $details,
            ];

            $generateEI = new GenerateEnergyInvoice();
            $invoiceID = $generateEI->generateInvoice(PDOFactory::getReadPDOInstance(), $data);

            if ($invoiceID) {

                $invoice = EnergyInvoice::get($invoiceID);
                if (!$invoice->getId()) {
                    throw new InternalCallableControllerException(sprintf("Invoice with id %u not found", $invoiceID));
                }

                $this->getView()
                    ->setInvoice($invoice)
                    ;

            } else {

                $lastInvoice = EnergyInvoice::getLast();
                if (!$lastInvoice->getId()) {
                    throw new InternalCallableControllerException(sprintf("Invoice with id %u not found", $invoiceID));
                }
                
                $this->getView()
                    ->setLastInvoice($lastInvoice)
                    ;

            }

            $this->getLayout()
                ->setWindowTitle('Invoice')
                ->setHeaderTitle('Invoice')
                ->addBreadCrumbs(new BreadCrumbs('Energy Consumption', '/EnergyConsumption'))
                ->addBreadCrumbs(new BreadCrumbs('New Invoice'))
                ;


        } catch (InternalCallableControllerException $e) {
            $this->getView()->getResult()->addError($e->getMessage());
        }

        return $this;
    }


    /**
     * Saving
     * @param int $id
     * @return Index
     * @throws HttpError4xxException
     */
    public function save(int $id) : Index
    {
        try {

            if (!UserAuth::isUserAuthenticated()) {
                throw new HttpError4xxException("Unauthorized user", 403);
            }

            if (!UserAuth::getAuthenticatedUser()->hasAccess(UserRights::MANAGE_ENERGY_CONSUMPTION)) {
                throw new HttpError4xxException("You are can't access to this section", 403);
            }

            $invoice = EnergyInvoice::get($id, true, PDOFactory::getReadPDOInstance());


            $this->getView()
                ->setInvoice($invoice);

            $this->getLayout()
                ->setWindowTitle('Invoice')
                ->setHeaderTitle('Invoice')
                ->addBreadCrumbs(new BreadCrumbs('Energy Consumption', '/EnergyConsumption'))
                ->addBreadCrumbs(new BreadCrumbs('Invoice Details'))
                ;


            if (!$invoice->getId()) {
                throw new InternalCallableControllerException(sprintf("Can't found invoice with id %u", $id));
            }

            // TEMP: Saving only status currently
            // TODO: Remaining keys
            if (null !== $this->getUserInputData('status')) {
                $invoice->setStatus(Strings::trim($this->getUserInputData('status')) ? 1 : 0);
            }

            $store = new Store($invoice);
            $result = $store->check($this->getView()->getResult());


            if ($result->isSuccess()) {
                // print_r($invoice);
                $store->update(PDOFactory::getWritePDOInstance());
                $this->getLayout()->setLocationRedirectUri('/EnergyConsumption/Invoice/' . $id);
            }


        } catch (InternalCallableControllerException $e) {
            $this->getView()->getResult()->addError($e->getMessage());
        }

        return $this;
    }
}
