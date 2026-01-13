<?php

namespace App;

use App\Db\PDOFactory;

/**
 * Class ConfiguredDevice
 * @package App
 */
class ConfiguredDevice
{
    /**
     * @var int $id
     */
    protected $id;

    /**
     * @var string $added_at
     */
    protected $added_at;

    /**
     * @var string $ip_address
     */
    protected $ip_address;

    /**
     * @var string $mac_address
     */
    protected $mac_address;

    /**
     * @var string $worker_name
     */
    protected $worker_name;

    /**
     * @var string $configuration
     */
    protected $configuration;

    /**
     * @var int $location_id
     */
    protected $location_id;

    /**
     * @var int $was_used
     */
    protected $was_used = 0;

    /**
     * @var Location $location
     */
    protected $location;

    /**
     * @var Miner[] $similar_devices
     */
    protected $similar_devices;

    /**
     * @var ConfiguredDevice[] $pool
     */
    protected static $pool;

    /**
     * @param int|string $id
     * @param bool $use_pool
     * @param \PDO $pdo
     * @return ConfiguredDevice
     */
    public static function get($id, $use_pool = true, \PDO $pdo = null)
    {
        if ($use_pool && isset(self::$pool[(int)$id])) {
            return self::$pool[(int)$id];
        }

        if (!isset($pdo)) {
            $pdo = PDOFactory::getReadPDOInstance();
        }

        $sth = $pdo->prepare("SELECT * FROM `configured_device` WHERE `id` = :id");
        $sth->execute(array("id" => $id));

        if ($sth->rowCount() !== 1) {
            self::$pool[(int)$id] = new self;
        } else {
            self::$pool[(int)$id] = $sth->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, "\App\ConfiguredDevice")[0];
        }

        return self::$pool[(int)$id];
    }

    /**
     * ConfiguredDevice constructor.
     */
    public function __construct()
    {
        $this->added_at = (new \DateTime())->format("Y-m-d H:i:s");
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): ConfiguredDevice
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddedAt(): ?string
    {
        return $this->added_at;
    }

    /**
     * @param string $added_at
     * @return $this
     */
    public function setAddedAt(string $added_at): ConfiguredDevice
    {
        $this->added_at = $added_at;
        return $this;
    }

    /**
     * @return string
     */
    public function getIpAddress(): ?string
    {
        return $this->ip_address;
    }

    /**
     * @param string $ip_address
     * @return $this
     */
    public function setIpAddress(string $ip_address): ConfiguredDevice
    {
        $this->ip_address = $ip_address;
        return $this;
    }

    /**
     * @return string
     */
    public function getMacAddress(): ?string
    {
        return $this->mac_address;
    }

    /**
     * @param string $mac_address
     * @return $this
     */
    public function setMacAddress(string $mac_address): ConfiguredDevice
    {
        $this->mac_address = $mac_address;
        return $this;
    }

    /**
     * @return string
     */
    public function getWorkerName(): ?string
    {
        return $this->worker_name;
    }

    /**
     * @param string $worker_name
     * @return $this
     */
    public function setWorkerName(string $worker_name): ConfiguredDevice
    {
        $this->worker_name = $worker_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfiguration(): ?string
    {
        return $this->configuration;
    }

    /**
     * @param string $configuration
     * @return $this
     */
    public function setConfiguration(string $configuration): ConfiguredDevice
    {
        $this->configuration = $configuration;
        return $this;
    }

    /**
     * @return int
     */
    public function getLocationId(): ?int
    {
        return $this->location_id;
    }

    /**
     * @param int $location_id
     * @return $this
     */
    public function setLocationId($location_id): ConfiguredDevice
    {
        $this->location_id = $location_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getWasUsed(): ?int
    {
        return $this->was_used;
    }

    /**
     * @param int $was_used
     * @return $this
     */
    public function setWasUsed(int $was_used): ConfiguredDevice
    {
        $this->was_used = $was_used ? 1 : 0;
        return $this;
    }

    /**
     * @param bool $force
     * @return Location
     */
    public function getLocation($force = false)
    {
        if (isset($this->location) && !$force) {
            return $this->location;
        }

        $this->location = Location::get($this->getLocationId());
        return $this->location;
    }

    /**
     * @param bool $force
     * @return Miner[]
     */
    public function getSimilarDevices($force = false): array
    {
        if (isset($this->similar_devices) && !$force) {
            return $this->similar_devices;
        }

        $this->similar_devices = Miner::getByMacAddress($this->mac_address);
        return $this->similar_devices;
    }

    /**
     * @param Miner[] $similar_devices
     * @return $this
     */
    public function setSimilarDevices(array $similar_devices): ConfiguredDevice
    {
        $this->similar_devices = $similar_devices;
        return $this;
    }
}