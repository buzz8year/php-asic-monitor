<?php
/**

 * Time: 14:09
 */

namespace App\Miners\CallableControllers;


use App\BreadCrumbs;
use App\Datetime;
use App\Db\PDOFactory;
use App\Front\CallableController;
use App\Front\CallableControllerInterface;
use App\HttpError4xxException;
use App\Locations\GetLocations4User;
use App\Miner;
use App\Miners\Store;
use App\Miners\Views\Html\EditView;
use App\Models\GetAllModels;
use App\Strings;
use App\UserAuth;
use App\UserRights;
use App\Views\ViewInterface;

class Add extends CallableController implements CallableControllerInterface
{
    /**
     * @inheritDoc
     * @return EditView|ViewInterface
     */
    public function getView()
    {
        return parent::getView();
    }


    /**
     * Форма редактирования майнера
     * @return Add
     * @throws HttpError4xxException
     */
    public function index(): Add
    {

        if (!UserAuth::isUserAuthenticated()) {
            throw new HttpError4xxException("Unauthorized user", 403);
        }

        if (!UserAuth::getAuthenticatedUser()->hasAccess(UserRights::MANAGE_UNITS)) {
            throw new HttpError4xxException("You are can't access to this section", 403);
        }

        $miner = new Miner();
        $models = (new GetAllModels())->getModels(PDOFactory::getReadPDOInstance());
        $locations = (new GetLocations4User(UserAuth::getAuthenticatedUser()))->getLocations(PDOFactory::getReadPDOInstance());

        $this->getView()
            ->setMiner($miner)
            ->setModels($models)
            ->setLocations($locations)
        ;

        $this->getLayout()
            ->setWindowTitle(sprintf("Add new miner"))
            ->setHeaderTitle(sprintf("Add new miner"))
            ->addBreadCrumbs(new BreadCrumbs("Miners", "/Miners"))
            ->addBreadCrumbs(new BreadCrumbs("Add"))
        ;



        return $this;
    }

    /**
     * Сохранение
     * @return Add
     * @throws HttpError4xxException
     */
    public function save(): Add
    {

        if (!UserAuth::isUserAuthenticated()) {
            throw new HttpError4xxException("Unauthorized user", 403);
        }

        if (!UserAuth::getAuthenticatedUser()->hasAccess(UserRights::MANAGE_UNITS)) {
            throw new HttpError4xxException("You are can't access to this section", 403);
        }

        $miner = new Miner();
        $models = (new GetAllModels())->getModels(PDOFactory::getReadPDOInstance());
        $locations = (new GetLocations4User(UserAuth::getAuthenticatedUser()))->getLocations(PDOFactory::getReadPDOInstance());


        $this->getView()
            ->setMiner($miner)
            ->setModels($models)
            ->setLocations($locations)
        ;

        $this->getLayout()
            ->setWindowTitle(sprintf("Add new ASIC", Strings::htmlspecialchars($miner->getIp()), Strings::htmlspecialchars($miner->getMac())))
            ->setHeaderTitle(sprintf("Add new ASIC", Strings::htmlspecialchars($miner->getIp()), Strings::htmlspecialchars($miner->getMac())))
            ->addBreadCrumbs(new BreadCrumbs("Miners", "/Miners"))
            ->addBreadCrumbs(new BreadCrumbs("Add"))
        ;

        $miner
            ->setIp(Strings::trim($this->getUserInputData("ip")))
            ->setPort((int)Strings::trim($this->getUserInputData("port")))
            ->setMac(Strings::trim($this->getUserInputData("mac")))
            ->setModelId((int)Strings::trim($this->getUserInputData("model_id")))
            ->setAllocationId((int)$this->getUserInputData("allocation_id"))
            ->setName(Strings::trim($this->getUserInputData("name")))
            ->setDescription(Strings::trim($this->getUserInputData("description")))
            ->setDtime(Datetime::create_force(Strings::trim($this->getUserInputData("dtime")))->getTimestamp())
            ->setStatus(Strings::trim($this->getUserInputData("status")) ? 1 : 0)
        ;

        if (!UserAuth::getAuthenticatedUser()->isLocationAllowed($miner->getAllocationId())) {
            throw new HttpError4xxException("Location with specified id is deny for you", 403);
        }

        $store = new Store($miner);
        $result = $store->check($this->getView()->getResult());

        if ($result->isSuccess()) {
            $store->add(PDOFactory::getWritePDOInstance());
            $this->getLayout()->setLocationRedirectUri("/Miners");
        }

        return $this;
    }


}