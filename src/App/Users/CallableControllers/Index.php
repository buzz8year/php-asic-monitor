<?php

namespace App\Users\CallableControllers;

use App\Front\CallableController;
use App\Front\CallableControllerInterface;
use App\HttpError4xxException;
use App\UserAuth;
use App\UserRights;
use App\Users\AllUsers;
use App\Users\Views\Html\ListUsersView;
use App\Views\ViewInterface;

class Index extends CallableController implements CallableControllerInterface
{
    /**
     * @inheritDoc
     * @return ListUsersView|ViewInterface
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
            ->setUsers((new AllUsers())->getUsers());

        $this->getLayout()
            ->setHeaderTitle("Current users accounts")
            ->setWindowTitle("Current users accounts");

        return $this;
    }
}