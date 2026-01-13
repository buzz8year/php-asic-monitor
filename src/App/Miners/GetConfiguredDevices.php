<?php

namespace App\Miners;

use App\ConfiguredDevice;
use App\Db\PDOFactory;

class GetConfiguredDevices implements GetConfiguredDevicesInterface
{
    /**
     * @var int $offset
     */
    protected $offset;

    /**
     * @var int $limit
     */
    protected $limit;

    /**
     * @var int $count
     */
    protected $count;

    /**
     * @return int
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function setOffset($offset): GetConfiguredDevices
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function setLimit($limit): GetConfiguredDevices
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return int
     */
    public function getCount(): ?int
    {
        return $this->count;
    }

    /**
     * @param int $count
     * @return $this
     */
    public function setCount(int $count): GetConfiguredDevices
    {
        $this->count = $count;
        return $this;
    }

    /**
     * @param \PDO $pdo
     * @return ConfiguredDevice[]
     */
    public function getDevices(\PDO $pdo = null)
    {
        if (!isset($pdo)) {
            $pdo = PDOFactory::getWritePDOInstance();
        }

        $sql_calc_found_rows = "";
        $limit = "";
        if (isset($this->limit) && isset($this->limit)) {
            $sql_calc_found_rows = "SQL_CALC_FOUND_ROWS";
            $limit = sprintf("LIMIT %u, %u", $this->offset, $this->limit);
        }

        $sth = $pdo->prepare("SELECT {$sql_calc_found_rows} * FROM `configured_device` ORDER BY `id` DESC {$limit}");
        $sth->execute();

        $sth->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, "\App\ConfiguredDevice");
        $devices = array();
        while ($device = $sth->fetch()) {
            $devices[] = $device;
        }

        if (isset($this->offset) && isset($this->limit)) {
            $this->setCount($pdo->query("select found_rows()")->fetch(\PDO::FETCH_NUM)[0]);
        } else {
            $this->setCount(sizeof($devices));
        }

        return $devices;
    }

}