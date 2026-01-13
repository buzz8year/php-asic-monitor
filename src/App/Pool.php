<?php

namespace App;


use App\Db\PDOFactory;

class Pool implements \JsonSerializable
{
    /**
     * Pool ID
     * @var int
     */
    protected $id;
    /**
     * Miner ID
     * @var int
     */
    protected $miner_id;
    /**
     * Miner stratum_url
     * @var string
     */
    protected $stratum_url;

    /** Miner stratum_url
     * @var string
     */
    protected $url;
    /**
     * Miner worker name
     * @var string
     */
    protected $worker;
    /**
     * Data pool
     * @var Pool[]
     */
    protected static $dataPool = [];

    /**
     * @param \PDO|null $pdo
     */
    public static function pool_init(\PDO $pdo = null) : void
    {
        if (!isset($pdo)) {
            $pdo = PDOFactory::getReadPDOInstance();
        }


        $sth = $pdo->query('SELECT * FROM pools');
        while ($miningPool = $sth->fetchObject('\App\Pool')) {
            self::$dataPool[(int)$miningPool->getMinerId()] = $miningPool;
        }

    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'miner_id' => $this->getMinerId(),
            'stratum_url' => $this->getStratumUrl(),
            'worker' => $this->getWorker(),
        ];
    }

    /**
     * Gets Pool by Miner ID
     * @param int $minerID
     * @param bool $use_pool
     * @param \PDO|null $pdo
     * @return Pool
     */
    public static function getByMinerId(int $miner_id, bool $use_pool = true, \PDO $pdo = null) : Pool
    {
        if ($use_pool && isset(self::$dataPool[$miner_id])) {
            return self::$dataPool[$miner_id];
        }

        if (!isset($pdo)) {
            $pdo = PDOFactory::getReadPDOInstance();
        }

        $sth = $pdo->prepare('SELECT * FROM pools WHERE miner_id = :miner_id');
        $sth->execute([
            ':miner_id' => $miner_id,
        ]);

        if ($sth->rowCount()) {
            self::$dataPool[$miner_id] = $sth->fetchObject('\App\Pool');
        } else {
            self::$dataPool[$miner_id] = new self;
        }

        return self::$dataPool[$miner_id];
    }
	
    /**
     * Returns id
     * @see id
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Sets id
     * @see id
     * @param int $id
     * @return Pool
     */
    public function setId(int $id) : Pool
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Returns Miner ID
     * @see id
     * @return int
     */
    public function getMinerId() : int
    {
        return $this->miner_id;
    }

    /**
     * Sets Miner id
     * @see miner_id
     * @param int $miner_id
     * @return Pool
     */
    public function setMinerId(int $miner_id) : Pool
    {
        $this->miner_id = $miner_id;
        return $this;
    }

    /**
     * Returns stratum_url
     * @see stratum_url
     * @return string
     */
    public function getStratumUrl() : ? string
    {
        return $this->stratum_url;
    }

    /**
     * Sets stratum_url
     * @see stratum_url
     * @param int $stratum_url
     * @return Pool
     */
    public function setStratumUrl(string $stratum_url) : Pool
    {
        $this->stratum_url = $stratum_url;
        return $this;
    }

    /**
     * Returns url
     * @see url
     * @return string
     */
    public function getUrl() : ? string
    {
        return $this->url;
    }

    /**
     * Sets url
     * @see url
     * @param int $url
     * @return Pool
     */
    public function setUrl(string $url) : Pool
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Returns worker
     * @see worker
     * @return string
     */
    public function getWorker() : ? string
    {
        return $this->worker;
    }

    /**
     * Sets worker
     * @see worker
     * @param int $worker
     * @return Pool
     */
    public function setWorker(string $worker) : Pool
    {
        $this->worker = $worker;
        return $this;
    }

}