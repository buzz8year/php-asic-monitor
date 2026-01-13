<?php

namespace App;

use App\Db\PDOFactory;

class Location implements \JsonSerializable
{
    /**
     * Location identifier
     * @var int
     */
    protected $id;
    /**
     * Name
     * @var string
     */
    protected $name;
    /**
     * Description
     * @var string
     */
    protected $description;
    /**
     * Networks (subnets)
     * @var string
     */
    protected $networks;
    /**
     * Pool of loaded Location objects
     * @var self[]
     */
    protected static $pool;

    /**
    * Get location by its identifier
     * @param int $id
     * @param bool $use_pool
     * @param \PDO|null $pdo
     * @return Location
     */
    public static function get(int $id, bool $use_pool = true, ?\PDO $pdo = null)
    {
        if ($use_pool && isset(self::$pool[$id])) {
            return self::$pool[$id];
        }

        if (!isset($pdo)) {
            $pdo = PDOFactory::getReadPDOInstance();
        }

        $sth = $pdo->prepare("select * from allocation where id = :id");
        $sth->execute(array(
            ":id" => $id
        ));

        if ($sth->rowCount()) {
            self::$pool[$id] = $sth->fetchObject('\App\Location');
        } else {
            self::$pool[$id] = new self;
        }

        return self::$pool[$id];
    }
	
	/**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return array(
            "id" => $this->getId(),
            "name" => $this->getName()
        );
    }
	
    /**
     * Get id
     * @see id
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set id
     * @see id
     * @param int $id
     * @return Location
     */
    public function setId(int $id): Location
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get name
     * @see name
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->name;
    }

    /**
     * Set name
     * @see name
     * @param string $name
     * @return Location
     */
    public function setName(string $name): Location
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get description
     * @see description
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set description
     * @see description
     * @param string $description
     * @return Location
     */
    public function setDescription(string $description): Location
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get networks
     * @see networks
     * @return string
     */
    public function getNetworks(): string
    {
        return $this->networks;
    }

    /**
     * Set networks
     * @see networks
     * @param string $networks
     * @return Location
     */
    public function setNetworks(string $networks): Location
    {
        $this->networks = $networks;
        return $this;
    }


    /**
     * Returns miners counted
     * @return string
     */
    public function countMiners(\PDO $pdo = null) : string
    {
        if (!isset($pdo)) {
            $pdo = PDOFactory::getReadPDOInstance();
        }

        $sth = $pdo->prepare('
            SELECT 
                COUNT(CASE WHEN status = 1 THEN 1 END) AS enabled,
                COUNT(CASE WHEN status = 0 THEN 1 END) AS disabled
            FROM miners
            WHERE allocation_id = :location_id
        ');
        $sth->execute([
            'location_id' => $this->id,
        ]);
        $result = $sth->fetch();

        return $result['enabled'] . ' <small>/ ' . $result['disabled'] . '</small>';
    }


}