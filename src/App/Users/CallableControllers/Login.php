<?php

namespace App\Users\CallableControllers;

use App\Db\PDOFactory;
use App\Front\CallableController;
use App\Front\CallableControllerInterface;
use App\Front\InternalCallableControllerException;
use App\User;
use App\UserAuth;
use App\Users\Store;

class Login extends CallableController implements CallableControllerInterface
{
    /**
     * @return CallableControllerInterface
     */
    public function index(): CallableControllerInterface
    {
        $this->getLayout()
            ->setHeaderTitle("Login")
            ->setWindowTitle("Login")
        ;

        return $this;
    }

    /**
     * @return CallableControllerInterface
     */
    public function doLogin(): CallableControllerInterface
    {
        try {

            $login = trim($this->getUserInputData("login"));
            if (!$login) {
                throw new InternalCallableControllerException("Please, specify you login");
            }


            $password = (string)$this->getUserInputData("password");

            if (!trim($password)) {
                throw new InternalCallableControllerException("Please, specify you password");
            }

            $user = User::getByLogin($login, PDOFactory::getReadPDOInstance());

            if (!$user->getId()) {
                throw new InternalCallableControllerException("Your login or password is incorrect");
            }

            if (password_verify($password, $user->getPassword()) !== true) {
                throw new InternalCallableControllerException("Your login or password is incorrect");
            }

            if (!$user->getActive()) {
                throw new InternalCallableControllerException("You account is disabled");
            }

            $store = new Store($user);
            $store->login($password);

            UserAuth::setAuthenticatedUser($user);

            $this->getLayout()->setLocationRedirectUri("/");

        } catch (InternalCallableControllerException $e) {
            $this->getView()->getResult()->addError($e->getMessage());
        }

        return $this;
    }
}