<?php
/**

 * Date: 13.06.2018
 * Time: 16:10
 */

namespace App\Locations\CallableControllers;


use App\BreadCrumbs;
use App\Db\PDOFactory;
use App\Front\CallableController;
use App\Front\CallableControllerInterface;
use App\HttpError4xxException;
use App\Locations\GetLocations4User;
use App\Locations\Views\Html\IndexHtmlView;
use App\UserAuth;
use App\UserRights;
use App\Views\ViewInterface;

class Index extends CallableController implements CallableControllerInterface
{
    /**
     * @inheritDoc
     * @return IndexHtmlView|ViewInterface
     */
    public function getView()
    {
        return parent::getView();
    }

    /**
     * Список локаций
     * @return Index
     * @throws HttpError4xxException
     */
    public function index(): Index
    {

        if (!UserAuth::isUserAuthenticated()) {
            throw new HttpError4xxException("Unauthorized user", 403);
        }

        if (!UserAuth::getAuthenticatedUser()->hasAccess(UserRights::MANAGE_LOCATIONS)) {
            throw new HttpError4xxException("You are can't access to this section", 403);
        }

        $this->getLayout()
            ->setWindowTitle("Locations")
            ->setHeaderTitle("Locations")
            ->addBreadCrumbs(new BreadCrumbs("Locations"))
            ;

        $locations_getter = new GetLocations4User(UserAuth::getAuthenticatedUser());
        $locations = $locations_getter->getLocations(PDOFactory::getReadPDOInstance());

        $this->getView()
            ->setLocations($locations)
            ;


        return $this;
    }

    /**
     * @return Index
     * @throws HttpError4xxException
     */
    public function listLocations(): Index
	{
        if (!UserAuth::isUserAuthenticated()) {
            throw new HttpError4xxException("Unauthorized user", 403);
        }

        if (!UserAuth::getAuthenticatedUser()->hasAccess(UserRights::MANAGE_LOCATIONS)) {
            throw new HttpError4xxException("You are can't access to this section", 403);
        }

		$locations_getter = new GetLocations4User(UserAuth::getAuthenticatedUser());
        $locations = $locations_getter->getLocations(PDOFactory::getReadPDOInstance());
		
		$this->getView()->setLocations($locations);
		return $this;
	}
}