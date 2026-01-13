<?php

namespace App\RealTime;

use App\LastStat;

class GetActiveStat implements GetLastStatInterface
{
    /**
     * @inheritdoc
     * @return LastStat[]
     */
    public function getStat(\PDO $pdo)
    {
        $sth = $pdo->query("select l.* from miners m join last_stats l on  m.id = l.miner_id where m.status = '1' order by m.allocation_id, m.ip");
        return $sth->fetchAll(\PDO::FETCH_CLASS, '\App\LastStat');

    }
}