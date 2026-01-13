<?php

namespace App\Miners;

use App\ConfiguredDevice;

interface GetConfiguredDevicesInterface
{
    /**
     * @param \PDO $pdo
     * @return ConfiguredDevice[]
     */
    public function getDevices(\PDO $pdo = null);
}