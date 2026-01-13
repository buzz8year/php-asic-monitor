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

class Edit extends CallableController implements CallableControllerInterface
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
     * @param $user_id
     * @return $this
     * @throws HttpError4xxException
     */
    public function index($user_id)
    {

        if (!UserAuth::isUserAuthenticated()) {
            throw new HttpError4xxException("Unauthorized user", 403);
        }

        if (!UserAuth::getAuthenticatedUser()->hasAccess(UserRights::MANAGE_USERS)) {
            throw new HttpError4xxException("You are can't access to this section", 403);
        }

        $user = User::get($user_id);

        if (!$user->getId()) {
            throw new HttpError4xxException("User with specified id not found", 404);
        }

        $this->getView()
            ->setUser($user)
            ->setUserRights(UserRights::getUserRightsArray())
            ->setLocations((new GetAllLocations())->getLocations(PDOFactory::getReadPDOInstance()))
        ;

        $this->getLayout()
            ->setWindowTitle("Edit user account")
            ->setHeaderTitle("Edit user account")
            ->addBreadCrumbs(new BreadCrumbs("User accounts", "Users/"))
            ->addBreadCrumbs(new BreadCrumbs("Edit user account"));

        return $this;
    }

    /**
     * @param $user_id
     * @return $this
     * @throws HttpError4xxException
     */
    public function save($user_id)
    {
        try {

            if (!UserAuth::isUserAuthenticated()) {
                throw new HttpError4xxException("Unauthorized user", 403);
            }

            if (!UserAuth::getAuthenticatedUser()->hasAccess(UserRights::MANAGE_USERS)) {
                throw new HttpError4xxException("You are can't access to this section", 403);
            }

            $user = User::get($user_id);
            if (!$user->getId()) {
                throw new HttpError4xxException("User with specified id not found", 404);
            }

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
                ->setActive($this->getUserInputData("active") ? 1 : 0)
                ;

            $new_password = (string)($this->getUserInputData("password"));
            if (trim($new_password)) {
                $user->setPassword(password_hash($new_password, PASSWORD_DEFAULT));
            }

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
                $store->update(PDOFactory::getWritePDOInstance());
                $this->getLayout()->setLocationRedirectUri("/Users");
            }

        } catch (InternalCallableControllerException $e) {
            $this->getView()->getResult()->addError($e->getMessage());
        }

        return $this;
    }
}