<?php

namespace App\RealTime\CallableControllers;

use App\BreadCrumbs;
use App\Db\PDOFactory;
use App\Front\CallableController;
use App\Front\CallableControllerInterface;
use App\Front\InternalCallableControllerException;
use App\HttpError4xxException;
use App\LastStat;
use App\Miner;
use App\RealTime\Flow4User;
use App\RealTime\GetActiveStat4User;
use App\RealTime\GraphsFactory;
use App\RealTime\Views\FlowViewInterface;
use App\RealTime\Views\Html\FullDataView;
use App\RealTime\Views\Html\IndexView;
use App\RealTime\Views\Html\UnitsDetailsView;
use App\UserAuth;
use App\Views\ViewInterface;

class Index extends CallableController implements CallableControllerInterface
{
    /**
     * @inheritDoc
     * @return IndexView|UnitsDetailsView|FullDataView|FlowViewInterface|ViewInterface
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

        $this->getLayout()
            ->setWindowTitle("Real Time")
            ->setHeaderTitle("Real Time")
            ->addBreadCrumbs(new BreadCrumbs("Real Time"))
            ;

        Miner::pool_init(); // speed up
        LastStat::pool_init(); // speed up

		if (isset($_POST['warn_params']) && $_POST['warn_params']) {
            $_SESSION['warn_params'] = $_POST['warn_params'];
        }

        $this->getView()
            ->setFlow(new Flow4User(UserAuth::getAuthenticatedUser(), true, PDOFactory::getReadPDOInstance()))
            ->setGraphs((new GraphsFactory(UserAuth::getAuthenticatedUser()))->getGraphs())
        ;

        return $this;
    }

    /**
     * @return Index
     * @throws HttpError4xxException
     */
    public function flow(): index
    {
        if (!UserAuth::isUserAuthenticated()) {
            throw new HttpError4xxException("Unauthorized user", 403);
        }

        $this->getLayout()
            ->setWindowTitle("Flow Data")
            ->setHeaderTitle("Flow Data")
            ->addBreadCrumbs(new BreadCrumbs("Real Time"))
        ;

        $this->getView()
            ->setFlow(new Flow4User(UserAuth::getAuthenticatedUser(), true, PDOFactory::getReadPDOInstance()))
        ;

        return $this;
    }

    /**
     * @return $this
     * @throws HttpError4xxException
     */
    public function unitsDetails()
    {
        if (!UserAuth::isUserAuthenticated()) {
            throw new HttpError4xxException("Unauthorized user", 403);
        }

        Miner::pool_init(); // speed up

        $this->getLayout()
            ->setWindowTitle("Units details")
            ->setHeaderTitle("Units details")
            ->addBreadCrumbs(new BreadCrumbs("Real Time", "/RealTime"))
            ->addBreadCrumbs(new BreadCrumbs("Units details"))
        ;

        $this->getView()
            ->setLastStats((new GetActiveStat4User(UserAuth::getAuthenticatedUser()))->getStat(PDOFactory::getReadPDOInstance()))
        ;

        return $this;
    }


    /**
     * @param $miner_id
     * @return $this
     * @throws HttpError4xxException
     */
    public function fullData($miner_id)
    {

        try {

            if (!UserAuth::isUserAuthenticated()) {
                throw new HttpError4xxException("Unauthorized user", 403);
            }

            if (!is_numeric($miner_id) || (int)$miner_id <= 0) {
                throw new InternalCallableControllerException("Bad miner id", 404);
            }

            $miner_id = (int)$miner_id; // fix type

            $miner = Miner::get($miner_id);
            if (!$miner->getId()) {
                throw new InternalCallableControllerException(sprintf("Miner with id %u not found", $miner_id));
            }

            if (!UserAuth::getAuthenticatedUser()->isLocationAllowed($miner->getAllocationId())) {
                throw new HttpError4xxException("You are can't access to this section, because this location is deny", 403);
            }

            $last_stat = LastStat::get($miner->getId());
            if (!$last_stat->getId()) {
                throw new InternalCallableControllerException(sprintf("Last stat data lost for miner with id %u", $miner->getId()));
            }

            $this->getView()
                ->setMiner($miner) // assign to view
                ->setLastStat($last_stat); // assign to view

            $this->getLayout()
                ->setWindowTitle(sprintf("Full information about ASIC with IP %s", $miner->getIp()))
                ->setHeaderTitle(sprintf("Full information about ASIC with IP %s", $miner->getIp()))
                ->addBreadCrumbs(new BreadCrumbs("Real Time", "/RealTime"))
                ->addBreadCrumbs(new BreadCrumbs("Full information"))
            ;

        } catch (InternalCallableControllerException $e) {
            $this->getView()->getResult()->addError($e->getMessage());
        }


        return $this;
    }
}