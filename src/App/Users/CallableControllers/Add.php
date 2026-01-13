<?php

namespace App\Users\CallableControllers;

use App\BreadCrumbs;
use App\Db\PDOFactory;
use App\Front\CallableController;
use App\Front\CallableControllerInterface;
use App\Front\InternalCallableControllerException;
use App\HttpError4xxException;
use App\Locations\GetAllLocations;
use App\Strings;
use App\User;
use App\UserAuth;
use App\UserRights;
use App\Users\Store;
use App\Users\Views\Html\AddUserView;
use App\Views\ViewInterface;

class Add extends CallableController implements CallableControllerInterface
{
    /**
     * @inheritDoc
     * @return AddUserView|ViewInterface
     */
    public function getView()
    {
        return parent::getView();
    }

    /**
     * @return $this
     * @throws HttpError4xxException
     */
    public function index()
    {

        if (!UserAuth::isUserAuthenticated()) {
            throw new HttpError4xxException("Unauthorized user", 403);
        }

        if (!UserAuth::getAuthenticatedUser()->hasAccess(UserRights::MANAGE_USERS)) {
            throw new HttpError4xxException("You are can't access to this section", 403);
        }

        $this->getView()
            ->setUser(new User())
            ->setUserRights(UserRights::getUserRightsArray())
            ->setLocations((new GetAllLocations())->getLocations(PDOFactory::getReadPDOInstance()))
        ;

        $this->getLayout()
            ->setWindowTitle("Create new user account")
            ->setHeaderTitle("Create new user account")
            ->addBreadCrumbs(new BreadCrumbs("User accounts", "Users/"))
            ->addBreadCrumbs(new BreadCrumbs("Create new user account"));

        return $this;
    }

    /**
     * @return $this
     * @throws HttpError4xxException
     */
    public function save()
    {
        try {

            if (!UserAuth::isUserAuthenticated()) {
                throw new HttpError4xxException("Unauthorized user", 403);
            }

            if (!UserAuth::getAuthenticatedUser()->hasAccess(UserRights::MANAGE_USERS)) {
                throw new HttpError4xxException("You are can't access to this section", 403);
            }

            $user = new User();

            $this->getView()
                ->setUser($user)
                ->setUserRights(UserRights::getUserRightsArray())
                ->setLocations((new GetAllLocations())->getLocations(PDOFactory::getReadPDOInstance()))
            ;

            $user
                ->setName(Strings::trim($this->getUserInputData("name")))
                ->setSurname(Strings::trim($this->getUserInputData("surname")))
                ->setPhone(Strings::trim($this->getUserInputData("phone")))
                ->setEmail(Strings::trim($this->getUserInputData("email")))
                ->setLogin(Strings::trim($this->getUserInputData("login")))
                ->setPassword((string)($this->getUserInputData("password")))
                ->setActive($this->getUserInputData("active") ? 1 : 0)
                ;

            $user_rights = array();
            if (is_array($this->getUserInputData("user_rights"))) {
                foreach ($this->getUserInputData("user_rights") as $access_id) {
                    if (is_numeric($access_id)) {
                        $user_rights[] = (int)$access_id;
                    }
                }
            }

            $user_locations = array();
            if (is_array($this->getUserInputData("user_locations"))) {
                foreach ($this->getUserInputData("user_locations") as $location_id) {
                    if (is_numeric($location_id)) {
                        $user_locations[] = $location_id;
                    }
                }
            }

            $user
                ->setAllAccess($user_rights)
                ->setAllowedLocations($user_locations)
            ;

            $store = new Store($user);
            $result = $store->check($this->getView()->getResult());
            if ($result->isSuccess()) {
                $store->add(PDOFactory::getWritePDOInstance());
                $this->getLayout()->setLocationRedirectUri("/Users");
            }

        } catch (InternalCallableControllerException $e) {
            $this->getView()->getResult()->addError($e->getMessage());
        }

        return $this;
    }
}