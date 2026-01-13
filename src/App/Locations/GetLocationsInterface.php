<?php
/**

 * Date: 13.06.2018
 * Time: 16:07
 */

namespace App\Locations;


use App\Location;

interface GetLocationsInterface
{
    /**
     * Возвращает локации
     * @param \PDO $pdo
     * @return Location[]
     */
    public function getLocations(\PDO $pdo);
}