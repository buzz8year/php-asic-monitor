<?php

namespace App\Miners;


class GetLocationMiners implements GetLocationMinersInterface
{
    /**
     * @inheritDoc
     */
    public function getMiners(\PDO $pdo, int $locationID = 1)
    {
        $sth = $pdo->prepare('
        	SELECT * 
        	FROM miners 
        	WHERE allocation_id = :location_id
            ORDER BY status DESC, ip
        ');

        $sth->execute([
        	'location_id' => $locationID
        ]);

        return $sth->fetchAll(\PDO::FETCH_CLASS, '\App\Miner');
    }

}