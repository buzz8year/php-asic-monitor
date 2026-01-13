<?php
/**

 * Date: 13.06.2018
 * Time: 16:08
 */

namespace App\Locations;


use App\Location;
use App\User;

class GetLocations4User implements GetLocationsInterface
{
    /**
     * Пользователь, для которого выполняется выборка
     * @var User
     */
    private $user;

    /**
     * GetLocations4User constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @inheritDoc
     * @return Location[]
     */
    public function getLocations(\PDO $pdo)
    {
        if (!sizeof($this->user->getAllowedLocations())) {
            return array();
        }

        $allowed_location = $this->user->getAllowedLocations();
        array_walk($allowed_location, "intval");

        $sth = $pdo->query(sprintf("
              select * from allocation
              where id in (%s) 
              order by name
         ", "'".(implode("', '", $allowed_location))."'")
        );

        return $sth->fetchAll(\PDO::FETCH_CLASS, '\App\Location');
    }

}