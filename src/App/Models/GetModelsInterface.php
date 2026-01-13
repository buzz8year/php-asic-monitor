<?php

namespace App\Models;

use App\Model;

interface GetModelsInterface
{
    /**
     * @param \PDO $pdo
     * @return Model[]
     */
    public function getModels(\PDO $pdo): array;
}