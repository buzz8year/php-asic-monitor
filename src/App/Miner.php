<?php

namespace App;

use App\Db\PDOFactory;

class Miner implements \JsonSerializable
{
    /**
     * Miner ID
     * @var int
     */
    protected $id;
    /**
     * Miner IP address
     * @var string
     */
    protected $ip;
    /**
     * Port for RPC requests to the miner
     * @var int
     */
    protected $port;
    /**
     * Miner MAC address
     * @var string
     */
    protected $mac;
    /**
     * Miner model ID
     * @var int
     */
    protected $model_id;
    /**
     * Allocation ID
     * @var int
     */
    protected $allocation_id;
    /**
     * Miner name
     * @var string
     */
    protected $name;
    /**
     * Miner description
     * @var string
     */
    protected $description;
    /**
     * Date when the miner was added to the database
     * @var int
     */
    protected $dtime;
    /**
     * Miner status
     * @var int 1 - monitoring in progress | 0 - monitoring stopped
     */
    protected $status;
    /**
     * Miners pool (for performance optimization)
     * @var Miner[]
     */
    public static $pool = array();

    /**
     * @var Miner[][] $pool_by_mac_addr
     */
    public static $pool_by_mac_addr = array();

    /**
    * Initializes the pool for all miners (to speed up database operations)
     * @param \PDO|null $pdo
     */
    public static function pool_init(?\PDO $pdo = null): void
    {
        if (!isset($pdo)) {
            $pdo = PDOFactory::getReadPDOInstance();
        }

        $sth = $pdo->query("select * from miners");
        while ($miner = $sth->fetchObject('\App\Miner')) {
            /* @var Miner $miner */
            self::$pool[(int)$miner->getId()] = $miner;
            self::$pool_by_mac_addr[mb_strtolower($miner->getMac())][] = $miner;
        }

    }


    /**
     * Get miner by ID
     * @param int $miner_id
     * @param bool $use_pool
     * @param \PDO|null $pdo
     * @return Miner
     */
    public static function get(int $miner_id, $use_pool = true, ?\PDO $pdo = null): Miner
    {
        if ($use_pool && isset(self::$pool[(int)$miner_id])) {
            return self::$pool[(int)$miner_id];
        }

        if (!isset($pdo)) {
            $pdo = PDOFactory::getReadPDOInstance();
        }

        $sth = $pdo->prepare("select * from miners where id = :id");
        $sth->execute(array(
            ":id" => $miner_id
        ));

        if ($sth->rowCount() !== 1) {
            self::$pool[(int)$miner_id] = new self;
        } else {
            self::$pool[(int)$miner_id] = $sth->fetchObject('\App\Miner');
        }

        return self::$pool[(int)$miner_id];

    }

    /**
     * @param $mac_address
     * @param bool $use_pool
     * @param \PDO|null $pdo
     * @return Miner[]
     */
    public static function getByMacAddress($mac_address, $use_pool = true, ?\PDO $pdo = null)
    {
        $mac_address = mb_strtolower($mac_address);
        if ($use_pool && isset(self::$pool_by_mac_addr[$mac_address])) {
            return self::$pool_by_mac_addr[$mac_address];
        }

        if (!isset($pdo)) {
            $pdo = PDOFactory::getReadPDOInstance();
        }

        $sth = $pdo->prepare("SELECT * FROM `miners` WHERE `mac` = :mac");
        $sth->execute(array("mac" => $mac_address));
        $sth->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, "\App\Miner");

        $devices = array();
        while ($device = $sth->fetch()) {
            $devices[] = $device;
        }

        self::$pool_by_mac_addr[$mac_address] = $devices;

        return $devices;
    }


    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return array(
            "id" => $this->getId(),
            "ip" => $this->getIp(),
            "port" => $this->getPort(),
            "mac" => $this->getMac(),
            "model_id" => $this->getModelId(),
            "allocation_id" => $this->getAllocationId(),
            "name" => $this->getName(),
            "shelf" => $this->getShelf(),
            "description" => $this->getDescription(),
            "dtime" => $this->getDtime(),
            "STATUS" => $this->getStatus(),
            "location" => $this->getLocation(),
            "pool" => $this->getPool(),
        );
    }


    /**
     * Get id
     * @see id
     * @return int
     */
    public function getId(): ?int
    {
        return (int)$this->id;
    }

    /**
     * Set id
     * @see id
     * @param int $id
     * @return Miner
     */
    public function setId(int $id): Miner
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get ip
     * @see ip
     * @return string
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * Set ip
     * @see ip
     * @param string $ip
     * @return Miner
     */
    public function setIp(string $ip): Miner
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * Get port
     * @see port
     * @return int
     */
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * Set port
     * @see port
     * @param int $port
     * @return Miner
     */
    public function setPort(int $port): Miner
    {
        $this->port = $port;
        return $this;
    }

    /**
     * Get mac
     * @see mac
     * @return string
     */
    public function getMac(): ?string
    {
        return $this->mac;
    }

    /**
     * Set mac
     * @see mac
     * @param string $mac
     * @return Miner
     */
    public function setMac(string $mac): Miner
    {
        $this->mac = $mac;
        return $this;
    }

    /**
     * Get model_id
     * @see model_id
     * @return int
     */
    public function getModelId(): ?int
    {
        return $this->model_id;
    }

    /**
     * Set model_id
     * @see model_id
     * @param int $model_id
     * @return Miner
     */
    public function setModelId(int $model_id): Miner
    {
        $this->model_id = $model_id;
        return $this;
    }

    /**
     * Get allocation_id
     * @see allocation_id
     * @return int
     */
    public function getAllocationId(): ?int
    {
        return $this->allocation_id;
    }

    /**
     * Set allocation_id
     * @see allocation_id
     * @param int $allocation_id
     * @return Miner
     */
    public function setAllocationId(int $allocation_id): Miner
    {
        $this->allocation_id = $allocation_id;
        return $this;
    }

    /**
     * Returns the miner's Model object
     * @return Model
     */
    public function getModel(): Model
    {
        return Model::get($this->model_id);
    }


    /**
     * @return string
     */
    public function getShelf() : ? string
    {
        if ( strpos($this->name, '.a') !== false ) {
            $exp = explode('.', $this->name);
            $stand = $exp[sizeof($exp) - 2];
            $shelf = $exp[sizeof($exp) - 1];
            return $stand . ' / ' . $shelf;
        }
        else {
            return '--';
        }
    }


    /**
     * Get name
     * @see name
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set name
     * @see name
     * @param string $name
     * @return Miner
     */
    public function setName(string $name): Miner
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get description
     * @see description
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set description
     * @see description
     * @param string $description
     * @return Miner
     */
    public function setDescription(string $description): Miner
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get dtime
     * @see dtime
     * @return int
     */
    public function getDtime(): ?int
    {
        return $this->dtime;
    }

    /**
     * Set dtime
     * @see dtime
     * @param int $dtime
     * @return Miner
     */
    public function setDtime(int $dtime): Miner
    {
        $this->dtime = $dtime;
        return $this;
    }

    /**
     * Get status
     * @see status
     * @return int
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * Set status
     * @see status
     * @param int $status
     * @return Miner
     */
    public function settatus(int $status): Miner
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Returns the last statistics
     * @return LastStat
     */
    public function getLastStat(): LastStat
    {
        return LastStat::get($this->id);
    }

    /**
     * Returns location
     * @return Location
     */
    public function getLocation(): Location
    {
        return Location::get($this->getAllocationId(), true);
    }

    /**
     * Returns Pool
     * @return Pool
     */
    public function getPool(): Pool
    {
        return Pool::getByMinerId($this->getId(), true);
    }


}