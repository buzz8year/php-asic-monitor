<?php
/**

 * Date: 31.05.2018
 * Time: 19:31
 */

namespace App\Miners\CallableControllers;


use App\BreadCrumbs;
use App\Db\PDOFactory;
use App\Front\CallableController;
use App\Front\CallableControllerInterface;
use App\HttpError4xxException;
use App\LastStat;
use App\Locations\GetLocations4User;
use App\Miner;
use App\Miners\GetActiveMiners4User;
use App\Miners\GetInactiveMiners4User;
use App\Pool;
use App\Miners\GetLocationMiners;
use App\Miners\MinersSummaryInfo;
use App\Miners\Views\Html\ListMinersView;
use App\UserAuth;
use App\UserRights;
use App\Views\ViewInterface;

class Index extends CallableController implements CallableControllerInterface
{
    /**
     * @inheritDoc
     * @return ViewInterface|ListMinersView
     */
    public function getView()
    {
        return parent::getView();
    }

    /**
     * Отображает майнеры
     * @param bool $display_inactive
     * @return $this
     * @throws HttpError4xxException
     */
    public function index($display_inactive = false)
    {
        if (!UserAuth::isUserAuthenticated()) {
            throw new HttpError4xxException("Unauthorized user", 403);
        }

        if (!UserAuth::getAuthenticatedUser()->hasAccess(UserRights::MANAGE_UNITS)) {
            throw new HttpError4xxException("You are can't access to this section", 403);
        }

        if (!$display_inactive) {
            $miners_getter = new GetActiveMiners4User(UserAuth::getAuthenticatedUser());
        } else {
            $miners_getter = new GetInactiveMiners4User(UserAuth::getAuthenticatedUser());
        }

        if (!$display_inactive) {
            $this->getLayout()
                ->setWindowTitle("List of active miners")
                ->setHeaderTitle("List of active miners")
                ->addBreadCrumbs(new BreadCrumbs("Active miners"))
            ;

            $this->getView()->setShowInactiveLink(true);

        } else {
            $this->getLayout()
                ->setWindowTitle("List of inactive miners")
                ->setHeaderTitle("List of inactive miners")
                ->addBreadCrumbs(new BreadCrumbs("Inactive miners"))
            ;

            $this->getView()->setShowInactiveLink(false);
        }

        Miner::pool_init(); // speed up
        LastStat::pool_init(); // speed up
        Pool::pool_init(); // speed up

        $locations = new GetLocations4User(UserAuth::getAuthenticatedUser());

        $this->getView()
            ->setMinersSummaryInfo(new MinersSummaryInfo(UserAuth::getAuthenticatedUser(), PDOFactory::getReadPDOInstance()))
            ->setMiners($miners_getter->getMiners(PDOFactory::getReadPDOInstance()))
            ->setLocations($locations->getLocations(PDOFactory::getReadPDOInstance()))
            ;

        return $this;
    }

    /**
     * Displaying miners up to location
     * @param int $location_id
     * @return $this
     * @throws HttpError4xxException
     */
    public function location(int $location_id = 1)
    {
        if (!UserAuth::isUserAuthenticated()) {
            throw new HttpError4xxException("Unauthorized user", 403);
        }

        if (!UserAuth::getAuthenticatedUser()->hasAccess(UserRights::MANAGE_UNITS)) {
            throw new HttpError4xxException("You are can't access to this section", 403);
        }

        if (!UserAuth::getAuthenticatedUser()->isLocationAllowed($location_id)) {
            throw new HttpError4xxException("You are can't access to this section, because location is deny", 403);
        }


        $miners = new GetLocationMiners();
        $locations = new GetLocations4User(UserAuth::getAuthenticatedUser());

        $this->getLayout()
            ->addBreadCrumbs(new BreadCrumbs("Miners Up To Allocation"))
        ;

        Miner::pool_init();
        LastStat::pool_init();

        $this->getView()
            ->setMiners($miners->getMiners(PDOFactory::getReadPDOInstance(), $location_id))
            ->setLocations($locations->getLocations(PDOFactory::getReadPDOInstance()))
            ->setCurrentLocationID($location_id)
            ;

        return $this;
    }
}