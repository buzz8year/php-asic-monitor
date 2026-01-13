<?php

namespace App;

use App\Db\PDOFactory;

class EnergyInvoice
{
    /**
     * Invoice identifier
     * @var int
     */
    protected $id;
    /**
     * Total uptime
     * @var int
     */
    protected $uptime_cumulative;
    /**
     * Number of miners included in the invoice
     * @var int
     */
    protected $miner_amount;
    /**
     * Miner model identifier
     * @var int
     */
    protected $model_id;

    /**
     * Miner model location identifier
     * @var int
     */
    protected $location_id;

    /**
     * Invoice generated FROM date
     * @var int
     */
    protected $start_date;

    /**
     * Invoice generated TO date
     * @var int
     */
    protected $invoice_date;
    /**
     * IDs of miners included in the invoice
     * @var string
     */
    protected $miners_data;
    /**
     * Status
     * @var int
     */
    protected $status;
    /**
     * Pool of previously loaded invoices
     * @var EnergyInvoice[]
     */
    protected static $pool = array();

    /**
    * Get invoice by its identifier
     * @param $id
     * @param bool $use_pool
     * @param \PDO|null $dbh
     * @return EnergyInvoice
     */
    public static function get($id, $use_pool = true, ?\PDO $dbh = null)
    {
        if ($use_pool && isset(self::$pool[(int)$id])) {
            return self::$pool[(int)$id];
        }

        if (!isset($dbh)) {
            $dbh = PDOFactory::getReadPDOInstance();
        }

        $sth = $dbh->prepare("select * from energy_invoice where id = :id");
        $sth->execute(array(
            ":id" => $id
        ));

        if ($sth->rowCount() !== 1) {
            self::$pool[(int)$id] = new self;
        } else {
            self::$pool[(int)$id] = $sth->fetchAll(\PDO::FETCH_CLASS, '\App\EnergyInvoice')[0];
        }

        return self::$pool[(int)$id];

    }


    /**
     * Last Invoice
     * @param \PDO|null $dbh
     * @return EnergyInvoice
     */
    public static function getLast(?\PDO $dbh = null)
    {
        if (!isset($dbh)) {
            $dbh = PDOFactory::getReadPDOInstance();
        }

        $sth = $dbh->query('SELECT * FROM energy_invoice ORDER BY id DESC LIMIT 1');

        return $sth->fetchAll(\PDO::FETCH_CLASS, '\App\EnergyInvoice')[0];
    }

    /**
     * Last Invoice
     * @param \PDO|null $dbh
     * @return EnergyInvoice[]
     */
    public function getUptimeRecords(?\PDO $dbh = null)
    {
        if (!isset($dbh)) {
            $dbh = PDOFactory::getReadPDOInstance();
        }

        $sth = $dbh->prepare('
            SELECT ur.miner_id, ur.uptime_invoice, ur.record_date, m.ip as miner_ip, m.dtime as miner_dtime, m.status as miner_status, p.stratum_url
            FROM uptime_record as ur 
            LEFT JOIN miners as m ON ur.miner_id = m.id 
            LEFT JOIN pools as p ON ur.miner_id = p.id 
            WHERE invoice_id = :invoice_id
            ORDER BY m.status, m.dtime DESC, ur.uptime_invoice ASC
        ');
        $sth->execute([
            'invoice_id' => $this->id,
        ]);

        return $sth->fetchAll(\PDO::FETCH_CLASS, '\App\EnergyInvoice');
    }

    /**
     * Returns id
     * @see id
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets id
     * @see id
     * @param int $id
     * @return EnergyInvoice
     */
    public function setId(int $id): EnergyInvoice
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Returns uptime_cumulative
     * @see uptime_cumulative
     * @return int
     */
    public function getUptimeCumulative(): int
    {
        return (int)$this->uptime_cumulative;
    }

    /**
     * Sets uptime_cumulative
     * @see uptime_cumulative
     * @param int $uptime_cumulative
     * @return EnergyInvoice
     */
    public function setUptimeCumulative(int $uptime_cumulative): EnergyInvoice
    {
        $this->uptime_cumulative = $uptime_cumulative;
        return $this;
    }

    /**
     * Returns miner_amount
     * @see miner_amount
     * @return int
     */
    public function getMinerAmount(): int
    {
        return (int)$this->miner_amount;
    }

    /**
     * Sets miner_amount
     * @see miner_amount
     * @param int $miner_amount
     * @return EnergyInvoice
     */
    public function setMinerAmount(int $miner_amount): EnergyInvoice
    {
        $this->miner_amount = $miner_amount;
        return $this;
    }

    /**
     * Returns model_id
     * @see model_id
     * @return int
     */
    public function getModelId(): int
    {
        return (int)$this->model_id;
    }

    /**
     * Sets model_id
     * @see model_id
     * @param int $model_id
     * @return EnergyInvoice
     */
    public function setModelId(int $model_id): EnergyInvoice
    {
        $this->model_id = $model_id;
        return $this;
    }

    /**
     * Returns location_id
     * @see location_id
     * @return int
     */
    public function getLocationId(): int
    {
        return (int)$this->location_id;
    }

    /**
     * Sets location_id
     * @see location_id
     * @param int $location_id
     * @return EnergyInvoice
     */
    public function setLocationId(int $location_id): EnergyInvoice
    {
        $this->location_id = $location_id;
        return $this;
    }

    /**
     * Returns start_date
     * @see start_date
     * @return int
     */
    public function getStartDate(): int
    {
        return (int)$this->start_date;
    }

    /**
     * Sets start_date
     * @see start_date
     * @param int $start_date
     * @return EnergyInvoice
     */
    public function setStartDate(int $start_date): EnergyInvoice
    {
        $this->start_date = $start_date;
        return $this;
    }

    /**
     * Returns invoice_date
     * @see invoice_date
     * @return int
     */
    public function getInvoiceDate(): int
    {
        return (int)$this->invoice_date;
    }

    /**
     * Sets invoice_date
     * @see invoice_date
     * @param int $invoice_date
     * @return EnergyInvoice
     */
    public function setInvoiceDate(int $invoice_date): EnergyInvoice
    {
        $this->invoice_date = $invoice_date;
        return $this;
    }

    /**
     * Returns miners_data
     * @see miners_data
     * @return string
     */
    public function getMinersData(): string
    {
        return (string)$this->miners_data;
    }

    /**
     * Sets miners_data
     * @see miners_data
     * @param string $miners_data
     * @return EnergyInvoice
     */
    public function setMinersData(string $miners_data): EnergyInvoice
    {
        $this->miners_data = $miners_data;
        return $this;
    }

    /**
     * Returns status
     * @see status
     * @return int
     */
    public function getStatus(): int
    {
        return (int)$this->status;
    }

    /**
     * Sets status
     * @see status
     * @param int $status
     * @return EnergyInvoice
     */
    public function setStatus(int $status): EnergyInvoice
    {
        $this->status = $status;
        return $this;
    }

}