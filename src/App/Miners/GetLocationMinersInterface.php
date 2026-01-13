<?php

namespace App\Miners;


use App\Miner;

interface GetLocationMinersInterface
{
    /**
     * Возвращает майнеры
     * @param \PDO $pdo
     * @return Miner[]
     */
    public function getMiners(\PDO $pdo, int $locationID);
}