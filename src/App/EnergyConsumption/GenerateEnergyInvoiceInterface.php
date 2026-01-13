<?php

namespace App\EnergyConsumption;

interface GenerateEnergyInvoiceInterface
{
    /**
     * Writes values of last_stats.uptime to uptime_record.uptime_value, based on those calculates energy_invoice
     * @param \PDO $dbh
     * @return []
     */
    public function generateInvoice(\PDO $dbh, array $data);
}