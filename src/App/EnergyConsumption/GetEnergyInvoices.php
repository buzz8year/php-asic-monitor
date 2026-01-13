<?php

namespace App\EnergyConsumption;

use App\EnergyInvoice;

class GetEnergyInvoices implements GetEnergyInvoicesInterface
{
    /**
     * @inheritdoc
     * @return EnergyInvoice[]
     */
    public function getInvoices(\PDO $pdo)
    {
        $sth = $pdo->query("select * from energy_invoice order by id desc");
        return $sth->fetchAll(\PDO::FETCH_CLASS, '\App\EnergyInvoice');
    }
    
}