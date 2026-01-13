<?php

namespace App\Miners;

use App\ConfiguredDevice;
use App\Db\PDOFactory;
use App\Result;
use App\Validator;

/**
 * Class StoreConfiguredDevice
 * @package App\Miners
 */
class StoreConfiguredDevice
{
    /**
     * @var ConfiguredDevice $device
     */
    protected $device;

    /**
     * StoreConfiguredDevice constructor.
     * @param ConfiguredDevice $device
     */
    public function __construct(ConfiguredDevice $device)
    {
        $this->device = $device;
    }

    /**
     * @param Result $result
     * @return Result
     */
    public function validate(Result $result): Result
    {
        if (!$this->device->getIpAddress() || !Validator::is_ipv4($this->device->getIpAddress())) {
            $result->addError("Empty or invalid IP address");
        }

        if (!$this->device->getMacAddress() || !Validator::is_mac($this->device->getMacAddress())) {
            $result->addError("Empty or invalid MAC address");
        }


        return $result;
    }

    /**
     * @param \PDO|null $dbh
     */
    public function save(\PDO $dbh = null)
    {
        if (!isset($dbh)) {
            $dbh = PDOFactory::getWritePDOInstance();
        }

        if ($this->device->getId()) {
            $this->update($dbh);
        } else {
            $this->add($dbh);
        }
    }

    /**
     * @param \PDO|null $dbh
     */
    public function add(\PDO $dbh = null)
    {
        if (!isset($dbh)) {
            $dbh = PDOFactory::getWritePDOInstance();
        }

        $sth = $dbh->prepare("
            INSERT INTO
              `configured_device`
            (
              `added_at`, 
              `ip_address`, 
              `mac_address`, 
              `worker_name`, 
              `configuration`,
              `location_id`,
              `was_used`
            )
            VALUES (
              :addedat,
              :ipaddress,
              :macaddress,
              :workername,
              :configuration,
              :locationid,
              :wasused            
            )  
        ");

        $sth->execute(array(
            "addedat" => $this->device->getAddedAt(),
            "ipaddress" => $this->device->getIpAddress(),
            "macaddress" => $this->device->getMacAddress(),
            "workername" => $this->device->getWorkerName(),
            "configuration" => $this->device->getConfiguration(),
            "locationid" => $this->device->getLocationId(),
            "wasused" => $this->device->getWasUsed(),
        ));

        $this->device->setId((int)$dbh->lastInsertId());
    }

    /**
     * @param \PDO|null $dbh
     */
    public function update(\PDO $dbh = null)
    {
        if (!isset($dbh)) {
            $dbh = PDOFactory::getWritePDOInstance();
        }

        $sth = $dbh->prepare("
            UPDATE
                `configured_device`
            SET
              `added_at` = :addedat,
              `ip_address` = :ipaddress,
              `mac_address` = :macaddress,
              `worker_name` = :workername,
              `configuration` = :configuration,
              `location_id` = :locationid,
              `was_used` = :wasused
            WHERE 
              `id` = :id      
        ");

        $sth->execute(array(
            "addedat" => $this->device->getAddedAt(),
            "ipaddress" => $this->device->getIpAddress(),
            "macaddress" => $this->device->getMacAddress(),
            "workername" => $this->device->getWorkerName(),
            "configuration" => $this->device->getConfiguration(),
            "locationid" => $this->device->getLocationId(),
            "wasused" => $this->device->getWasUsed(),
            "id" => $this->device->getId()
        ));
    }

    /**
     * @param \PDO|null $dbh
     */
    public function delete(\PDO $dbh = null)
    {
        if (!isset($dbh)) {
            $dbh = PDOFactory::getWritePDOInstance();
        }

        $sth = $dbh->prepare("DELETE FROM `configured_device` WHERE `id` = :id");
        $sth->execute(array("id" => $this->device->getId()));
    }
}