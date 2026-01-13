<?php

namespace App\EnergyConsumption;

interface GetEnergyInvoicesInterface
{
    /**
     * @param \PDO $dbh
     * @return []
     */
    public function getInvoices(\PDO $dbh);
}