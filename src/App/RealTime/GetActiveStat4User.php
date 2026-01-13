<?php

namespace App\RealTime;

use App\LastStat;
use App\User;

class GetActiveStat4User implements GetLastStatInterface
{

    /**
     * @var User
     */
    private $user;

    /**
     * GetActiveStat4User constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @inheritdoc
     * @return LastStat[]
     */
    public function getStat(\PDO $pdo)
    {

        if (!$this->user->getAllowedLocations()) {
            return array();
        }

        $allowed_location = $this->user->getAllowedLocations();
        array_walk($allowed_location, "intval");
        $allowed_location_string = "m.allocation_id in ('".(implode("', '", $allowed_location))."')";

        $sth = $pdo->query(sprintf("
            select l.* 
            from miners m 
            join last_stats l on  m.id = l.miner_id 
            where m.status = '1' && %s
            order by m.allocation_id, m.ip
        ",
            $allowed_location_string
        ));

        return $sth->fetchAll(\PDO::FETCH_CLASS, '\App\LastStat');
    }
}