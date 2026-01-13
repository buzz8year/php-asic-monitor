<?php

namespace App\RealTime;

use App\LastStat;

interface GetLastStatInterface
{
    /**
     * @param \PDO $dbh
     * @return LastStat[]
     */
    public function getStat(\PDO $dbh);
}