<?php
/**

 * Date: 13.06.2018
 * Time: 16:08
 */

namespace App\Locations;


use App\Location;

class GetAllLocations implements GetLocationsInterface
{
    /**
     * @inheritDoc
     * @return Location[]
     */
    public function getLocations(\PDO $pdo)
    {
        $sth = $pdo->query("select * from allocation order by name");
        return $sth->fetchAll(\PDO::FETCH_CLASS, '\App\Location');
    }

}