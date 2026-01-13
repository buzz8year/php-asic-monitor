<?php

namespace App;


use App\Db\PDOFactory;

class LastStat implements \JsonSerializable
{
    /**
     * Status ОК
     */
    const STATUS_OK = 1;
    /**
     * Status WARNING
     */
    const STATUS_WARNING = 2;
    /**
     * Status FAILED
     */
    const STATUS_FAILED = 3;
    /**
     * Data pool
     * @var self
     */
    protected static $pool = array();

    /**
     * Record ID
     * @var int
     */
    protected $id;
    /**
     * Miner ID
     * @var int
     */
    protected $miner_id;
    /**
     * Miner identifier (not unique, friendly id)
     * @var string
     */
    protected $unique_id;
    /**
     * Whether the ASIC is running
     * @var int 0|1
     */
    protected $up;
    /**
     * Timestamp when the record was created
     * @var int
     */
    protected $dtime;
    /**
     * Seconds since work start (mining time, not power-on time)
     * @var int
     */
    protected $uptime;
    /**
     * Miner type
     * @var string
     */
    protected $type;
    /**
     * bmminer version
     * @var string
     */
    protected $bmminer;
    /**
     * Hardware version
     * @var string
     */
    protected $hardware;
    /**
     * Firmware version
     * @var string
     */
    protected $firmware;
    /**
     * Chip model
     * @var string
     */
    protected $model;
    /**
     * Hashrate over the last 5 seconds
     * @var float
     */
    protected $hashrate;
    /**
     * Average working hashrate
     * @var float
     */
    protected $hashrate_avg;
    /**
     * Average operating frequency
     * @var string
     */
    protected $freq_avg;
    /**
     * Total frequency
     * @var float
     */
    protected $freq_total;
    /**
     * Number of boards
     * @var int
     */
    protected $miner_count;
    /**
     * Number of fans
     * @var int
     */
    protected $fan_num;
    /**
     * Fan speed
     * @var string
     */
    protected $fan_speed;
    /**
     * Chips per board
     * @var string
     */
    protected $chips;
    /**
     * Number of working chips
     * @var int
     */
    protected $chips_alive;
    /**
     * Number of defective chips
     * @var int
     */
    protected $chips_bad;
    /**
     * Number of inactive chips
     * @var int
     */
    protected $chips_lost;
    /**
     * Current board hashrate (GH/s)
     * @var string
     */
    protected $chain_rate;
    /**
     * Total hashrate across all boards (GH/s)
     * @var float
     */
    protected $chain_rate_total;
    /**
     * Ideal board hashrate (GH/s)
     * @var string
     */
    protected $chain_rateideal;
    /**
     * Total ideal hashrate across all boards (GH/s)
     * @var float
     */
    protected $chain_rateideal_total;
    /**
     * Unknown field
     * @var string
     */
    protected $chain_offset;
    /**
     * Hardware error percentage
     * @var float
     */
    protected $hw_error_rate;
    /**
     * Error readings on boards
     * @var string
     */
    protected $chain_hw;
    /**
     * Unknown information
     * @var string
     */
    protected $chain_xtime;
    /**
     * Chip temperatures on boards
     * @var string
     */
    protected $temp_chips;
    /**
     * Maximum chip temperature
     * @var int
     */
    protected $temp_chips_max;
    /**
     * Board temperatures
     * @var string
     */
    protected $temp_boards;
    /**
     * Maximum board temperature
     * @var int
     */
    protected $temp_board_max;

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return array_merge(
            get_object_vars($this),
            array(
                "status" => $this->getStatus(),
                "warnings" => $this->getWarnings(),
                "miner" => $this->getMiner()
            )
        );
    }


    /**
     * Returns the last statistics for a given miner_id
     * @param int $miner_id
     * @param bool $use_pool
     * @param \PDO|null $pdo
     * @return LastStat|mixed
     */
    public static function get(int $miner_id, $use_pool = true, ?\PDO $pdo = null)
    {
        if ($use_pool && isset(self::$pool[(int)$miner_id])) {
            return self::$pool[(int)$miner_id];
        }

        if (!isset($pdo)) {
            $pdo = PDOFactory::getReadPDOInstance();
        }

        $sth = $pdo->prepare("select * from last_stats where miner_id = :miner_id");
        $sth->execute(array(
           ":miner_id" => $miner_id
        ));

        if ($sth->rowCount() !== 1) {
            self::$pool[(int)$miner_id] = new self;
        } else {
            self::$pool[(int)$miner_id] = $sth->fetchObject('\App\LastStat');
        }

        return self::$pool[(int)$miner_id];
    }

    /**
     * Initialize the pool with the latest states
     * @param \PDO|null $pdo
     */
    public static function pool_init(?\PDO $pdo = null): void
    {
        if (!isset($pdo)) {
            $pdo = PDOFactory::getReadPDOInstance();
        }

        $sth = $pdo->query("select * from last_stats");
        while ($last_stat = $sth->fetchObject('\App\LastStat')) {
            /* @var LastStat $last_stat*/
            self::$pool[(int)$last_stat->getMinerId()] = $last_stat;
        }

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
     * @return LastStat
     */
    public function setId(int $id): LastStat
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Returns miner_id
     * @see miner_id
     * @return int
     */
    public function getMinerId(): int
    {
        return $this->miner_id;
    }

    /**
     * Sets miner_id
     * @see miner_id
     * @param int $miner_id
     * @return LastStat
     */
    public function setMinerId(int $miner_id): LastStat
    {
        $this->miner_id = $miner_id;
        return $this;
    }

    /**
     * Returns the Miner object
     * @return Miner
     */
    public function getMiner()
    {
        return Miner::get($this->miner_id, true);
    }

    /**
     * Returns unique_id
     * @see unique_id
     * @return string
     */
    public function getUniqueId(): ?string
    {
        return $this->unique_id;
    }

    /**
     * Sets unique_id
     * @see unique_id
     * @param string $unique_id
     * @return LastStat
     */
    public function setUniqueId(string $unique_id): LastStat
    {
        $this->unique_id = $unique_id;
        return $this;
    }

    /**
     * Returns up
     * @see up
     * @return int
     */
    public function getUp(): int
    {
        return (int)$this->up;
    }

    /**
     * Sets up
     * @see up
     * @param int $up
     * @return LastStat
     */
    public function setUp(int $up): LastStat
    {
        $this->up = $up;
        return $this;
    }

    /**
     * Returns dtime
     * @see dtime
     * @return int
     */
    public function getDtime(): int
    {
        return (int)$this->dtime;
    }

    /**
     * Sets dtime
     * @see dtime
     * @param int $dtime
     * @return LastStat
     */
    public function setDtime(int $dtime): LastStat
    {
        $this->dtime = $dtime;
        return $this;
    }

    /**
     * Returns uptime
     * @see uptime
     * @return int
     */
    public function getUptime(): int
    {
        return (int)$this->uptime;
    }

    /**
     * Sets uptime
     * @see uptime
     * @param int $uptime
     * @return LastStat
     */
    public function setUptime(int $uptime): LastStat
    {
        $this->uptime = $uptime;
        return $this;
    }

    /**
     * Returns type
     * @see type
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Sets type
     * @see type
     * @param string $type
     * @return LastStat
     */
    public function setType(string $type): LastStat
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Returns bmminer
     * @see bmminer
     * @return string
     */
    public function getBmminer(): ?string
    {
        return $this->bmminer;
    }

    /**
     * Sets bmminer
     * @see bmminer
     * @param string $bmminer
     * @return LastStat
     */
    public function setBmminer(string $bmminer): LastStat
    {
        $this->bmminer = $bmminer;
        return $this;
    }

    /**
     * Returns hardware
     * @see hardware
     * @return string
     */
    public function getHardware(): ?string
    {
        return $this->hardware;
    }

    /**
     * Sets hardware
     * @see hardware
     * @param string $hardware
     * @return LastStat
     */
    public function setHardware(string $hardware): LastStat
    {
        $this->hardware = $hardware;
        return $this;
    }

    /**
     * Returns firmware
     * @see firmware
     * @return string
     */
    public function getFirmware(): ?string
    {
        return $this->firmware;
    }

    /**
     * Sets firmware
     * @see firmware
     * @param string $firmware
     * @return LastStat
     */
    public function setFirmware(string $firmware): LastStat
    {
        $this->firmware = $firmware;
        return $this;
    }

    /**
     * Returns model
     * @see model
     * @return string
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * Set model
     * @see model
     * @param string $model
     * @return LastStat
     */
    public function setModel(string $model): LastStat
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Returns hashrate
     * @see hashrate
     * @return float
     */
    public function getHashrate(): ?float
    {
        return $this->hashrate;
    }

    /**
     * Sets hashrate
     * @see hashrate
     * @param float $hashrate
     * @return LastStat
     */
    public function setHashrate(float $hashrate): LastStat
    {
        $this->hashrate = $hashrate;
        return $this;
    }

    /**
     * Returns hashrate_avg
     * @see hashrate_avg
     * @return float
     */
    public function getHashrateAvg(): ?float
    {
        return $this->hashrate_avg;
    }

    /**
     * Sets hashrate_avg
     * @see hashrate_avg
     * @param float $hashrate_avg
     * @return LastStat
     */
    public function setHashrateAvg(float $hashrate_avg): LastStat
    {
        $this->hashrate_avg = $hashrate_avg;
        return $this;
    }

    /**
     * Returns freq_avg
     * @see freq_avg
     * @return string
     */
    public function getFreqAvg(): ?string
    {
        return $this->freq_avg;
    }

    /**
     * Sets freq_avg
     * @see freq_avg
     * @param string $freq_avg
     * @return LastStat
     */
    public function setFreqAvg(string $freq_avg): LastStat
    {
        $this->freq_avg = $freq_avg;
        return $this;
    }

    /**
     * Ger freq_total
     * @see freq_total
     * @return float
     */
    public function getFreqTotal(): ?float
    {
        return $this->freq_total;
    }

    /**
     * Set freq_total
     * @see freq_total
     * @param float $freq_total
     * @return LastStat
     */
    public function setFreqTotal(float $freq_total): LastStat
    {
        $this->freq_total = $freq_total;
        return $this;
    }

    /**
     * Returns miner_count
     * @see miner_count
     * @return int
     */
    public function getMinerCount(): ?int
    {
        return $this->miner_count;
    }

    /**
     * Set miner_count
     * @see miner_count
     * @param int $miner_count
     * @return LastStat
     */
    public function setMinerCount(int $miner_count): LastStat
    {
        $this->miner_count = $miner_count;
        return $this;
    }

    /**
     * Returns fan_num
     * @see fan_num
     * @return int
     */
    public function getFanNum(): ?int
    {
        return $this->fan_num;
    }

    /**
     * Set fan_num
     * @see fan_num
     * @param int $fan_num
     * @return LastStat
     */
    public function setFanNum(int $fan_num): LastStat
    {
        $this->fan_num = $fan_num;
        return $this;
    }

    /**
     * Returns fan_speed
     * @see fan_speed
     * @return string
     */
    public function getFanSpeed(): ?string
    {
        return $this->fan_speed;
    }

    /**
     * Set fan_speed
     * @see fan_speed
     * @param string $fan_speed
     * @return LastStat
     */
    public function setFanSpeed(string $fan_speed): LastStat
    {
        $this->fan_speed = $fan_speed;
        return $this;
    }

    /**
     * Returns chips
     * @see chips
     * @return string
     */
    public function getChips(): ?string
    {
        return $this->chips;
    }

    /**
     * Set chips
     * @see chips
     * @param string $chips
     * @return LastStat
     */
    public function setChips(string $chips): LastStat
    {
        $this->chips = $chips;
        return $this;
    }

    /**
     * Returns chips_alive
     * @see chips_alive
     * @return int
     */
    public function getChipsAlive(): ?int
    {
        return $this->chips_alive;
    }

    /**
     * Set chips_alive
     * @see chips_alive
     * @param int $chips_alive
     * @return LastStat
     */
    public function setChipsAlive(int $chips_alive): LastStat
    {
        $this->chips_alive = $chips_alive;
        return $this;
    }

    /**
     * Returns chips_bad
     * @see chips_bad
     * @return int
     */
    public function getChipsBad(): ?int
    {
        return $this->chips_bad;
    }

    /**
     * Set chips_bad
     * @see chips_bad
     * @param int $chips_bad
     * @return LastStat
     */
    public function setChipsBad(int $chips_bad): LastStat
    {
        $this->chips_bad = $chips_bad;
        return $this;
    }

    /**
     * Returns chips_lost
     * @see chips_lost
     * @return int
     */
    public function getChipsLost(): ?int
    {
        return $this->chips_lost;
    }

    /**
     * Set chips_lost
     * @see chips_lost
     * @param int $chips_lost
     * @return LastStat
     */
    public function setChipsLost(int $chips_lost): LastStat
    {
        $this->chips_lost = $chips_lost;
        return $this;
    }

    /**
     * Returns chain_rate
     * @see chain_rate
     * @return string
     */
    public function getChainRate(): ?string
    {
        return $this->chain_rate;
    }

    /**
     * Set chain_rate
     * @see chain_rate
     * @param string $chain_rate
     * @return LastStat
     */
    public function setChainRate(string $chain_rate): LastStat
    {
        $this->chain_rate = $chain_rate;
        return $this;
    }

    /**
     * Returns chain_rate_total
     * @see chain_rate_total
     * @return float
     */
    public function getChainRateTotal(): ?float
    {
        return $this->chain_rate_total;
    }

    /**
     * Set chain_rate_total
     * @see chain_rate_total
     * @param float $chain_rate_total
     * @return LastStat
     */
    public function setChainRateTotal(float $chain_rate_total): LastStat
    {
        $this->chain_rate_total = $chain_rate_total;
        return $this;
    }

    /**
     * Returns chain_rateideal
     * @see chain_rateideal
     * @return string
     */
    public function getChainRateideal(): ?string
    {
        return $this->chain_rateideal;
    }

    /**
     * Set chain_rateideal
     * @see chain_rateideal
     * @param string $chain_rateideal
     * @return LastStat
     */
    public function setChainRateideal(string $chain_rateideal): LastStat
    {
        $this->chain_rateideal = $chain_rateideal;
        return $this;
    }

    /**
     * Returns chain_rateideal_total
     * @see chain_rateideal_total
     * @return float
     */
    public function getChainRateidealTotal(): ?float
    {
        return $this->chain_rateideal_total;
    }

    /**
     * Set chain_rateideal_total
     * @see chain_rateideal_total
     * @param float $chain_rateideal_total
     * @return LastStat
     */
    public function setChainRateidealTotal(float $chain_rateideal_total): LastStat
    {
        $this->chain_rateideal_total = $chain_rateideal_total;
        return $this;
    }

    /**
     * Returns chain_offset
     * @see chain_offset
     * @return string
     */
    public function getChainOffset(): ?string
    {
        return $this->chain_offset;
    }

    /**
     * Set chain_offset
     * @see chain_offset
     * @param string $chain_offset
     * @return LastStat
     */
    public function setChainOffset(string $chain_offset): LastStat
    {
        $this->chain_offset = $chain_offset;
        return $this;
    }

    /**
     * Returns hw_error_rate
     * @see hw_error_rate
     * @return float
     */
    public function getHwErrorRate(): ?float
    {
        return $this->hw_error_rate;
    }

    /**
     * Set hw_error_rate
     * @see hw_error_rate
     * @param float $hw_error_rate
     * @return LastStat
     */
    public function setHwErrorRate(float $hw_error_rate): LastStat
    {
        $this->hw_error_rate = $hw_error_rate;
        return $this;
    }

    /**
     * Returns chain_hw
     * @see chain_hw
     * @return string
     */
    public function getChainHw(): ?string
    {
        return $this->chain_hw;
    }

    /**
     * Set chain_hw
     * @see chain_hw
     * @param string $chain_hw
     * @return LastStat
     */
    public function setChainHw(string $chain_hw): LastStat
    {
        $this->chain_hw = $chain_hw;
        return $this;
    }

    /**
     * Returns chain_xtime
     * @see chain_xtime
     * @return string
     */
    public function getChainXtime(): ?string
    {
        return $this->chain_xtime;
    }

    /**
     * Set chain_xtime
     * @see chain_xtime
     * @param string $chain_xtime
     * @return LastStat
     */
    public function setChainXtime(string $chain_xtime): LastStat
    {
        $this->chain_xtime = $chain_xtime;
        return $this;
    }

    /**
     * Returns temp_chips
     * @see temp_chips
     * @return string
     */
    public function getTempChips(): ?string
    {
        return $this->temp_chips;
    }

    /**
     * Set temp_chips
     * @see temp_chips
     * @param string $temp_chips
     * @return LastStat
     */
    public function setTempChips(string $temp_chips): LastStat
    {
        $this->temp_chips = $temp_chips;
        return $this;
    }

    /**
     * Returns temp_chips_max
     * @see temp_chips_max
     * @return int
     */
    public function getTempChipsMax(): ?int
    {
        return $this->temp_chips_max;
    }

    /**
     * Set temp_chips_max
     * @see temp_chips_max
     * @param int $temp_chips_max
     * @return LastStat
     */
    public function setTempChipsMax(int $temp_chips_max): LastStat
    {
        $this->temp_chips_max = $temp_chips_max;
        return $this;
    }

    /**
     * Returns temp_boards
     * @see temp_board
     * @return string
     */
    public function getTempBoards(): ?string
    {
        return $this->temp_boards;
    }

    /**
     * Set temp_board
     * @see temp_board
     * @param string $temp_boards
     * @return LastStat
     */
    public function setTempBoard(string $temp_boards): LastStat
    {
        $this->temp_boards = $temp_boards;
        return $this;
    }

    /**
     * Ger temp_board_max
     * @see temp_board_max
     * @return int
     */
    public function getTempBoardMax(): ?int
    {
        return $this->temp_board_max;
    }

    /**
     * Set temp_board_max
     * @see temp_board_max
     * @param int $temp_board_max
     * @return LastStat
     */
    public function setTempBoardMax(int $temp_board_max): LastStat
    {
        $this->temp_board_max = $temp_board_max;
        return $this;
    }

    /**
     * Ger current Status
     * @return int
     */
    public function getStatus(): int
    {


        if (!$this->up || (time() - $this->getDtime() > 10*60)) {
            return self::STATUS_FAILED;
        }

        if ($this->getWarnings()) {
            return self::STATUS_WARNING;
        }

        return self::STATUS_OK;
    }

    public function getWarnings(): array
    {
        // In case of changes, please also
        // change \App\RealTime\Flow.php
        // TODO: move to constants

        if (!$this->getUp()) {
            return array();
        }

        $model = $this->getMiner()->getModel();

        $return = array();

        // If connection lag warnings not desired
        if (!empty($_SESSION['warn_params']['conn_lag'])) {
            if ((time() - $this->getDtime() > 60*20) && (time() - $this->getDtime() <= 60*10)) {
                $return[] = sprintf("Connection LAG");
            }
        }
        
        if ($this->getChipsAlive() !== $model->getTotalChips()) {
            $return[] = sprintf("Low alive chips (%u / %u)", $this->getChipsAlive(), $model->getTotalChips());
        }

        if ($this->getChipsBad() > 0) {
            $return[] = sprintf("Bad chips found (%u)", $this->getChipsBad());
        }

        if ($this->getChipsLost() > 0) {
            $return[] = sprintf("Lost chips found (%u)", $this->getChipsLost());
        }

        if (!empty($_SESSION['warn_params']['hw_error'])) {
	        if ($this->getHwErrorRate() >= 0.097) {
	            // TODO: hw_rate raised from 0.001 to 0.097
	            $return[] = sprintf("High HW error rate: %0.4f", $this->getHwErrorRate());
	        }
	    }

        if ($this->getTempChipsMax() >= 90) {
            $return[] = sprintf("High chips temp: %u &#176;C", $this->getTempChipsMax());
        }

        if ($this->getTempBoardMax() >= 80) {
            $return[] = sprintf("High board temp: %u &#176;C", $this->getTempBoardMax());
        }

        $chain_rate_diff = $this->getChainRateTotal() / $this->getChainRateidealTotal() ;

        if ($chain_rate_diff <= 0.97) {
            // TODO: cut from 0.97 to 0.60
            $return[] = sprintf("Low chain rate: %0.2f%%", $chain_rate_diff * 100);
        }

        return $return;
    }


}