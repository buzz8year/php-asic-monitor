<?php

namespace App;

use App\Db\PDOFactory;

class User
{
    /**
     * User identifier
     * @var int
     */
    protected $id;
    /**
     * User first name
     * @var string
     */
    protected $name;
    /**
     * Last name
     * @var string
     */
    protected $surname;
    /**
     * Phone number (may be used for SMS notifications)
     * @var string
     */
    protected $phone;
    /**
     * Email address
     * @var string
     */
    protected $email;
    /**
     * Login
     * @var string
     */
    protected $login;
    /**
     * Password
     * @var string
     */
    protected $password;
    /**
     * Last login datetime (UTC)
     * @var string
     */
    protected $last_login;
    /**
     * Last login IP address
     * @var string
     */
    protected $last_ip;
    /**
     * Active flag
     * @var int
     */
    protected $active = 1;
    /**
     * All user rights
     * @var array
     */
    public static $all_access;
    /**
     * Allowed locations for the user
     * @var array
     */
    public static $allowed_locations;

    /**
     * Returns a user by its identifier. Note: if the user with specified
     * identifier does not exist, an empty User object is returned
     * @param $id
     * @param \PDO $pdo
     * @return User
     */
    public static function get($id, ?\PDO $pdo = null): User
    {
        if (!isset($pdo)) {
            $pdo = PDOFactory::getReadPDOInstance();
        }

        $sth = $pdo->prepare("select * from users where id = :id");
        $sth->execute(array(
            ":id" => $id
        ));

        if ($sth->rowCount() === 1) {
            return $sth->fetchObject('\App\User');
        }

        return new User();
    }

    /**
     * Returns a user by login. Note: if the user does not exist, an empty User object is returned
     * @param string $login
     * @param \PDO|null $pdo
     * @return User
     */
    public static function getByLogin(string $login, ?\PDO $pdo = null): User
    {
        if (!isset($pdo)) {
            $pdo = PDOFactory::getReadPDOInstance();
        }

        $sth = $pdo->prepare("select * from users where login = :login");
        $sth->execute(array(
            ":login" => $login
        ));

        if ($sth->rowCount() === 1) {
            return $sth->fetchObject('\App\User');
        }

        return new User();
    }

    /**
     * Returns id
     * @see id
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets id
     * @see id
     * @param int $id
     * @return User
     */
    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Returns name
     * @see name
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets name
     * @see name
     * @param string $name
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Returns surname
     * @see surname
     * @return string
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * Sets surname
     * @see surname
     * @param string $surname
     * @return User
     */
    public function setSurname(string $surname): User
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * Returns phone
     * @see phone
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Sets phone
     * @see phone
     * @param string $phone
     * @return User
     */
    public function setPhone(string $phone): User
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Returns email
     * @see email
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Sets email
     * @see email
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Returns login
     * @see login
     * @return string
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * Sets login
     * @see login
     * @param string $login
     * @return User
     */
    public function setLogin(string $login): User
    {
        $this->login = $login;
        return $this;
    }

    /**
     * Returns password
     * @see password
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Sets password
     * @see password
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Returns last_login
     * @see last_login
     * @return string
     */
    public function getLastLogin(): ?string
    {
        return $this->last_login;
    }

    /**
     * Sets last_login
     * @see last_login
     * @param string $last_login
     * @return User
     */
    public function setLastLogin(string $last_login): User
    {
        $this->last_login = $last_login;
        return $this;
    }

    /**
     * Returns last_ip
     * @see last_ip
     * @return string
     */
    public function getLastIp(): ?string
    {
        return $this->last_ip;
    }

    /**
     * Sets last_ip
     * @see last_ip
     * @param string $last_ip
     * @return User
     */
    public function setLastIp(string $last_ip): User
    {
        $this->last_ip = $last_ip;
        return $this;
    }

    /**
     * Returns active flag
     * @see active
     * @return int
     */
    public function getActive(): int
    {
        return $this->active;
    }

    /**
     * Sets active flag
     * @see active
     * @param int $active
     * @return User
     */
    public function setActive(int $active): User
    {
        $this->active = $active;
        return $this;
    }

    /**
     * Sets user rights
     * @param array $all_access
     * @return User
     */
    public function setAllAccess(array $all_access): User
    {
        self::$all_access = $all_access;
        return $this;
    }

    /**
     * Returns all access identifiers for the user
     * @param \PDO $pdo
     * @return array
     */
    public function getAllAccess(?\PDO $pdo = null): array
    {
        if (isset(self::$all_access)) {
            return self::$all_access;
        }

        if (!isset($pdo)) {
            $pdo = PDOFactory::getReadPDOInstance();
        }

        $sth = $pdo->prepare("select access_id from user_rights where user_id = :user_id");
        $sth->execute(array(
            ":user_id" => $this->id
        ));

        self::$all_access = array();
        while ($row = $sth->fetch(\PDO::FETCH_NUM)) {
            self::$all_access[] = $row[0];
        }

        return self::$all_access;
    }

    /**
     * Returns whether the user has the specified access right
     * @param int $access_id
     * @return bool
     */
    public function hasAccess(int $access_id): bool
    {
        return array_search($access_id, $this->getAllAccess()) !== false;
    }

    /**
     * Sets allowed locations for the user
     * @param $allowed_locations
     * @return User
     */
    public function setAllowedLocations($allowed_locations): User
    {
        self::$allowed_locations = $allowed_locations;
        return $this;
    }

    /**
     * Returns all locations available to the user
     * @param \PDO|null $pdo
     * @return array
     */
    public function getAllowedLocations(?\PDO $pdo = null): array
    {
        if (isset(self::$allowed_locations)) {
            return self::$allowed_locations;
        }

        if (!isset($pdo)) {
            $pdo = PDOFactory::getReadPDOInstance();
        }

        $sth = $pdo->prepare("select location_id from user_locations where user_id = :user_id");
        $sth->execute(array(
            "user_id" => $this->id
        ));

        self::$allowed_locations = array();
        while ($row = $sth->fetch(\PDO::FETCH_NUM)) {
            self::$allowed_locations[] = $row[0];
        }

        return self::$allowed_locations;
    }

    /**
     * Returns whether the specified location is allowed for the user
     * @param int $location_id
     * @return bool
     */
    public function isLocationAllowed(int $location_id): bool
    {
        return array_search($location_id, $this->getAllowedLocations()) !== false;
    }

}