<?php
/**

 * Date: 31.05.2018
 * Time: 20:12
 */

namespace App\Miners;


use App\User;

class GetActiveMiners4User implements GetMinersInterface
{
    /**
     * Пользователь, для которого выбираются устройства
     * @var User
     */
    private $user;

    /**
     * GetActiveMiners4User constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @inheritDoc
     */
    public function getMiners(\PDO $pdo)
    {

        if (!sizeof($this->user->getAllowedLocations())) {
            return array();
        }

        $allowed_location = $this->user->getAllowedLocations();
        array_walk($allowed_location, "intval");

        $sth = $pdo->query(sprintf("
              select * from miners 
              where 
                status = '1' 
                && allocation_id in (%s)
              order by ip
        ",
            "'".(implode("', '", $allowed_location))."'"
        ));

        return $sth->fetchAll(\PDO::FETCH_CLASS, '\App\Miner');
    }

}