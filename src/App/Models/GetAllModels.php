<?php

namespace App\Models;

class GetAllModels implements GetModelsInterface
{
    /**
     * @inheritdoc
     */
    public function getModels(\PDO $pdo): array
    {
        $sth = $pdo->query("select * from models order by name");
        return $sth->fetchAll(\PDO::FETCH_CLASS, '\App\Model');
    }
}