<?php

namespace App\EnergyConsumption;

use App\EnergyInvoice;
use App\Result;

class Store
{
    /**
     * @var EnergyInvoice
     */
    private $invoice;

    /**
     * Store constructor.
     * @param EnergyInvoice $invoice
     */
    public function __construct(EnergyInvoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * @param Result $result
     * @return Result
     */
    public function check(Result $result): Result
    {
        if ($this->invoice->getUptimeCumulative() <= 0) {
            $result->addError("Uptime cannot be zero");
        }

        if (!is_numeric($this->invoice->getMinerAmount()) || $this->invoice->getMinerAmount() <= 0) {
            $result->addError("Miners amount specified with error");
        }

        // TEMP: Further, model_id should be "<= 0"
        if ($this->invoice->getModelId() < 0) {
            $result->addError("Model specified with error");
        }

        if ($this->invoice->getInvoiceDate() <= 0) {
            $result->addError("Invoice date cannot be less or equal than zero");
        }

        if ($this->invoice->getStatus() !== 0 && $this->invoice->getStatus() !== 1) {
            $result->addError("Status specified with error");
        }

        return $result;
    }

    /**
     * Обновляет данные в СУБД
     * @param \PDO $pdo
     */
    public function update(\PDO $pdo): void
    {
        $sth = $pdo->prepare("
            UPDATE energy_invoice
            SET uptime_cumulative = :uptime_cumulative,
                miner_amount = :miner_amount,
                model_id = :model_id,
                invoice_date = :invoice_date,
                status = :status,
                miners_data = :miners_data
            WHERE id = :id
        ");

        $sth->execute(array(
            ":uptime_cumulative" => $this->invoice->getUptimeCumulative(),
            ":miner_amount" => $this->invoice->getMinerAmount(),
            ":model_id" => $this->invoice->getModelId(),
            ":invoice_date" => $this->invoice->getInvoiceDate(),
            ":status" => $this->invoice->getStatus(),
            ":miners_data" => $this->invoice->getMinersData(),
            ":id" => $this->invoice->getId()
        ));
    }
}