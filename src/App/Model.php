<?php

namespace App;

use App\Db\PDOFactory;

class Model
{
    /**
     * Model identifier
     * @var int
     */
    protected $id;
    /**
     * Model name
     * @var string
     */
    protected $name;
    /**
     * Power unit
     * @var string
     */
    protected $unit;
    /**
     * Model description
     * @var string
     */
    protected $description;
    /**
     * Chips count description
     * @var string
     */
    protected $chips;
    /**
     * Temperature keys
     * @var string
     */
    protected $temp_keys;
    /**
     * Cache of previously loaded models
     * @var Model[]
     */
    protected static $pool = array();

    /**
     * Get model by its identifier
     * @param $id
     * @param bool $use_pool
     * @param \PDO|null $dbh
     * @return Model
     */
    public static function get($id, $use_pool = true, ?\PDO $dbh = null)
    {
        if ($use_pool && isset(self::$pool[(int)$id])) {
            return self::$pool[(int)$id];
        }

        if (!isset($dbh)) {
            $dbh = PDOFactory::getReadPDOInstance();
        }

        $sth = $dbh->prepare("select * from models where id = :id");
        $sth->execute(array(
            ":id" => $id
        ));

        if ($sth->rowCount() !== 1) {
            self::$pool[(int)$id] = new self;
        } else {
            self::$pool[(int)$id] = $sth->fetchAll(\PDO::FETCH_CLASS, '\App\Model')[0];
        }

        return self::$pool[(int)$id];

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
     * @return Model
     */
    public function setId(int $id): Model
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Returns name
     * @see name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets name
     * @see name
     * @param string $name
     * @return Model
     */
    public function setName(string $name): Model
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Returns unit
     * @see unit
     * @return string
     */
    public function getUnit(): string
    {
        return $this->unit;
    }

    /**
     * Sets unit
     * @see unit
     * @param string $unit
     * @return Model
     */
    public function setUnit(string $unit): Model
    {
        $this->unit = $unit;
        return $this;
    }

    /**
     * Returns description
     * @see description
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Sets description
     * @see description
     * @param string $description
     * @return Model
     */
    public function setDescription(string $description): Model
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Returns chips description
     * @see chips
     * @return string
     */
    public function getChips(): string
    {
        return $this->chips;
    }

    /**
     * Sets chips description
     * @see chips
     * @param string $chips
     * @return Model
     */
    public function setChips(string $chips): Model
    {
        $this->chips = $chips;
        return $this;
    }

    /**
     * Returns total number of chips
     * @return int
     */
    public function getTotalChips(): int
    {
        $return = 0;

        $chips = explode(",", $this->getChips());

        array_walk($chips, function($cnt) use (&$return) {
            $return += (int)$cnt;
        });

        return $return;
    }

    /**
     * Returns temp_keys
     * @see temp_keys
     * @return string
     */
    public function getTempKeys(): string
    {
        return $this->temp_keys;
    }

    /**
     * Sets temp_keys
     * @see temp_keys
     * @param string $temp_keys
     * @return Model
     */
    public function setTempKeys(string $temp_keys): Model
    {
        $this->temp_keys = $temp_keys;
        return $this;
    }


}