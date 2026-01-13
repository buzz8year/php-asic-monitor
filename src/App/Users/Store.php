<?php

namespace App\Users;

use App\Datetime;
use App\Db\PDOFactory;
use App\Result;
use App\User;

class Store
{
    /**
     * @var User
     */
    private $user;

    /**
     * Store constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param Result $result
     * @return Result
     */
    public function check(Result $result): Result
    {
        if (!trim($this->user->getLogin())) {
            $result->addError("Please, enter user login");
        }

        if (!trim($this->user->getPassword())) {
            $result->addError("Please, enter user password");
        }

        if ($result->isSuccess()) {
            $user = User::getByLogin($this->user->getLogin());
            if ($user && $user->getId() && $user->getId() !== $this->user->getId()) {
                $result->addError("User with specified login is already exist");
            }
        }


        return $result;
    }

    /**
     * @param \PDO|null $pdo
     */
    public function add(\PDO $pdo = null): void
    {
        if (!isset($pdo)) {
            $pdo = PDOFactory::getWritePDOInstance();
        }

        $sth = $pdo->prepare("
          insert into users
          (id, name, surname, phone, email, login, password, last_login, last_ip, active) 
          values 
          (null, :name, :surname, :phone, :email, :login, :password, :last_login, :last_ip, :active)
        ");

        $sth->execute(array(
            "name" => $this->user->getName(),
            "surname" => $this->user->getSurname(),
            "phone" => $this->user->getPhone(),
            "email" => $this->user->getEmail(),
            "login" => $this->user->getLogin(),
            "password" => password_hash($this->user->getPassword(), PASSWORD_DEFAULT),
            "last_login" => $this->user->getLastLogin(),
            "last_ip" => $this->user->getLastIp(),
            "active" => $this->user->getActive()
        ));

        $this->user->setId((int)$pdo->lastInsertId());

        $this->updateUserRights($pdo);
        $this->updateUserLocations($pdo);
    }

    /**
     * @param \PDO|null $pdo
     * @return void
     */
    public function update(\PDO $pdo = null): void
    {
        if (!isset($pdo)) {
            $pdo = PDOFactory::getWritePDOInstance();
        }

        $sth = $pdo->prepare("
           update users
           set
            name = :name,
            surname = :surname,
            phone = :phone,
            email = :email,
            password = :password,
            active = :active
          where
            id = :id           
        ");

        $sth->execute(array(
            ":name" => $this->user->getName(),
            ":surname" => $this->user->getSurname(),
            ":phone" => $this->user->getPhone(),
            ":email" => $this->user->getEmail(),
            ":password" => $this->user->getPassword(),
            ":active" => $this->user->getActive(),
            ":id" => $this->user->getId()
        ));

        $this->updateUserRights($pdo);
        $this->updateUserLocations($pdo);
    }

    /**
     * @param \PDO $pdo
     * @return void
     */
    protected function updateUserRights(\PDO $pdo): void
    {
        $sth = $pdo->prepare("delete from user_rights where user_id = :user_id");
        $sth->execute(array(
            ":user_id" => $this->user->getId()
        ));

        if (!sizeof($this->user->getAllAccess())) {
            return;
        }

        $sth = $pdo->prepare("insert into user_rights (user_id, access_id) values (:user_id, :access_id)");
        foreach ($this->user->getAllAccess() as $access_id) {
            $sth->execute(array(
                ":user_id" => $this->user->getid(),
                ":access_id" => $access_id
            ));
        }
    }

    /**
     * @param \PDO $pdo
     * @return void
     */
    protected function updateUserLocations(\PDO $pdo): void
    {
        $sth = $pdo->prepare("delete from user_locations where user_id = :user_id");
        $sth->execute(array(
            ":user_id" => $this->user->getId()
        ));

        if (!sizeof($this->user->getAllowedLocations())) {
            return;
        }

        $sth = $pdo->prepare("insert into user_locations (user_id, location_id) values (:user_id, :location_id)");
        foreach ($this->user->getAllowedLocations() as $location_id) {
            $sth->execute(array(
                ":user_id" => $this->user->getid(),
                ":location_id" => $location_id
            ));
        }
    }

    /**
     * @param string $password
     * @param \PDO|null $pdo
     */
    public function login(string $password, \PDO $pdo = null): void
    {
        $last_ip = $_SERVER['REMOTE_ADDR'] ?? "0.0.0.0";
        $last_login = Datetime::now()->format("Y-m-d H:i:s");
        $password_hash = $this->user->getPassword();

        if (password_needs_rehash($password_hash, PASSWORD_DEFAULT)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
        }

        if (!isset($pdo)) {
            $pdo = PDOFactory::getWritePDOInstance();
        }

        $sth = $pdo->prepare("
            update users
            set
              last_ip = :last_ip,
              last_login = :last_login,
              password = :password_hash
            where
              id = :id        
        ");

        $sth->execute(array(
            ":last_ip" => $last_ip,
            ":last_login" => $last_login,
            ":password_hash" => $password_hash,
            ":id" => $this->user->getId()
        ));
    }
}