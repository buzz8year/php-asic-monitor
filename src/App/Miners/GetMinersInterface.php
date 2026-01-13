<?php
/**

 * Date: 31.05.2018
 * Time: 20:06
 */

namespace App\Miners;


use App\Miner;

interface GetMinersInterface
{
    /**
     * Возвращает майнеры
     * @param \PDO $pdo
     * @return Miner[]
     */
    public function getMiners(\PDO $pdo);
}