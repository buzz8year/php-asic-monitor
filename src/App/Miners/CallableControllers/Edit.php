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
use App\Front\InternalCallableControllerException;
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
use App\Miners\RequestHostname;
use App\Utils\Request;
use App\Result;

class Edit extends CallableController implements CallableControllerInterface
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
     * @param int $id
     * @return Edit
     * @throws HttpError4xxException
     */
    public function index(int $id): Edit
    {
        try {

            if (!UserAuth::isUserAuthenticated()) {
                throw new HttpError4xxException("Unauthorized user", 403);
            }

            if (!UserAuth::getAuthenticatedUser()->hasAccess(UserRights::MANAGE_UNITS)) {
                throw new HttpError4xxException("You cannot access this section", 403);
            }

            $miner = Miner::get($id, true, PDOFactory::getReadPDOInstance());
            $models = (new GetAllModels())->getModels(PDOFactory::getReadPDOInstance());
            $locations = (new GetLocations4User(UserAuth::getAuthenticatedUser()))->getLocations(PDOFactory::getReadPDOInstance());

            $this->getView()
                ->setMiner($miner)
                ->setModels($models)
                ->setLocations($locations)
            ;

            $this->getLayout()
                ->setWindowTitle(sprintf("Edit miner with IP %s and MAC %s", Strings::htmlspecialchars($miner->getIp()), Strings::htmlspecialchars($miner->getMac())))
                ->setHeaderTitle(sprintf("Edit miner with IP %s and MAC %s", Strings::htmlspecialchars($miner->getIp()), Strings::htmlspecialchars($miner->getMac())))
                ->addBreadCrumbs(new BreadCrumbs("Miners", "/Miners"))
                ->addBreadCrumbs(new BreadCrumbs("Edit"))
            ;

            if (!$miner->getId()) {
                throw new InternalCallableControllerException(sprintf("Cannot find miner with id %u", $id));
            }


        } catch (InternalCallableControllerException $e) {
            $this->getView()->getResult()->addError($e->getMessage());
        }

        return $this;
    }

    /**
     * Сохранение
     * @param int $id
     * @return Edit
     * @throws HttpError4xxException
     */
    public function save(int $id): Edit
    {
        try {

            if (!UserAuth::isUserAuthenticated()) {
                throw new HttpError4xxException("Unauthorized user", 403);
            }

            if (!UserAuth::getAuthenticatedUser()->hasAccess(UserRights::MANAGE_UNITS)) {
                throw new HttpError4xxException("You cannot access this section", 403);
            }

            $miner = Miner::get($id, true, PDOFactory::getReadPDOInstance());
            $models = (new GetAllModels())->getModels(PDOFactory::getReadPDOInstance());
            $locations = (new GetLocations4User(UserAuth::getAuthenticatedUser()))->getLocations(PDOFactory::getReadPDOInstance());

            $this->getView()
                ->setMiner($miner)
                ->setModels($models)
                ->setLocations($locations)
            ;

            $this->getLayout()
                ->setWindowTitle(sprintf("Edit miner with IP %s and MAC %s", Strings::htmlspecialchars($miner->getIp()), Strings::htmlspecialchars($miner->getMac())))
                ->setHeaderTitle(sprintf("Edit miner with IP %s and MAC %s", Strings::htmlspecialchars($miner->getIp()), Strings::htmlspecialchars($miner->getMac())))
                ->addBreadCrumbs(new BreadCrumbs("Miners", "/Miners"))
                ->addBreadCrumbs(new BreadCrumbs("Edit"))
            ;

            if (!$miner->getId()) {
                throw new InternalCallableControllerException(sprintf("Cannot find miner with id %u", $id));
            }

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
                throw new HttpError4xxException("Location with specified id is not accessible", 403);
                // throw new HttpError4xxException("Location with specified id is denied for you", 403);
            }

            $store = new Store($miner);
            $result = $store->check($this->getView()->getResult());

            if ($result->isSuccess()) {
                $store->update(PDOFactory::getWritePDOInstance());
                $this->getLayout()->setLocationRedirectUri("/Miners");
            }


        } catch (InternalCallableControllerException $e) {
            $this->getView()->getResult()->addError($e->getMessage());
        }

        return $this;
    }


    /**
     * Сохранение
     * @param int $id
     * @return Edit
     */
    public function requestHostname(int $id)
    {
        $this->getView()->setResponse(RequestHostname::sshRequestByMinerId($id))->getResult();
        return $this;
    }


}