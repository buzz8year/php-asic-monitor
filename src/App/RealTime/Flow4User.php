<?php

namespace App\RealTime;

use App\User;

class Flow4User extends Flow
{
    /**
     * @var User
     */
    protected $user;
    /**
     * Flow constructor.
     * @param User $user
     * @param bool $use_cache
     * @param \PDO|null $pdo
     */
    public function __construct(User $user, $use_cache = true, \PDO $pdo = null)
    {
        $this->user = $user;
        parent::__construct($use_cache, $pdo);
    }



}