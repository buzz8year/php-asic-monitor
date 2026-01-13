<?php

namespace App\Users;

use App\Db\PDOFactory;

class AllUsers implements GetUsersInterface
{
    /**
     * PDO object to use for operations
     * @var \PDO
     */
    private $pdo;

    /**
     * AllUsers constructor.
     * @param \PDO|null $pdo
     */
    public function __construct(?\PDO $pdo = null)
    {
        if (!isset($pdo)) {
            $pdo = PDOFactory::getReadPDOInstance();
        }

        $this->pdo = $pdo;
    }

    /**
     * @inheritdoc
     */
    public function getUsers(): array
    {
        $sth = $this->pdo->query("select * from users order by name, surname");
        return $sth->fetchAll(\PDO::FETCH_CLASS, '\App\User');
    }
}