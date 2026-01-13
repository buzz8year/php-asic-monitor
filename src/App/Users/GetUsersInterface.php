<?php

namespace App\Users;

use App\User;

interface GetUsersInterface
{
    /**
     * @return User[]
     */
    public function getUsers(): array;
}