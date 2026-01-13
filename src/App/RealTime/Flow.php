<?php

namespace App\RealTime;

use App\Db\PDOFactory;
use App\LastStat;
use App\User;

class Flow
{
    /**
     * @var array
     */
    protected static $cache = array();
    /**
     * @var int
     */
    protected $total_unit = 0;
    /**
     * @var int
     */
    protected $warning_unit = 0;
    /**
     * @var int
     */
    protected $failed_unit = 0;
    /**
     * @var float
     */
    protected $ideal_hashrate = 0.0;
    /**
     * Текущий хешрейт
     * @var float
     */
    protected $current_hashrate = 0.0;
    /**
     * @var float
     */
    protected $temp_chips_avg = 0.0;
    /**
     * @var int
     */
    protected $temp_chips_max = 0;
    /**
     * @var int
     */
    protected $temp_chips_min = 0;
    /**
     * @var LastStat[]
     */
    protected $warning_units = array();
    /**
     * @var LastStat[]
     */
    protected $failed_units = array();
    /**
     * @var User
     */
    protected $user;

    /**
     * Flow constructor.
     * @param bool $use_cache
     * @param \PDO|null $pdo
     */
    public function __construct($use_cache = true, \PDO $pdo = null)
    {
        if ($use_cache && sizeof(self::$cache)) {
            foreach (self::$cache as $attribute => $value) {
                $this->$attribute = $value;
            }
            return;
        }

        if (!isset($pdo)) {
            $pdo = PDOFactory::getReadPDOInstance();
        }

        if ($this->user) {
            if (!sizeof($this->user->getAllowedLocations())) {
                return;
            }

            $allowed_location = $this->user->getAllowedLocations();
            array_walk($allowed_location, "intval");
            $allowed_location_string = " && m.allocation_id in ('".(implode("', '", $allowed_location))."')";
        } else {
            $allowed_location_string = '';
        }

        $sth = $pdo->prepare(sprintf("
            select
                count(*) as total_unit,
                sum(case when
                  up = '1' && (unix_timestamp() - l.dtime <= 60*20) and (
                      ((unix_timestamp() - l.dtime > 60*20) and (unix_timestamp() - l.dtime <= 60*30)) 
                      or (chips_bad > 0)
                      or (chips_lost > 0)
                      -- @todo if model count
                      or (hw_error_rate  >= 0.097)
                      or (temp_chips_max >= 90)
                      or (temp_board_max >= 80)
                      or (chain_rate_total / chain_rateideal_total <= 0.60)
                  )
                  then 1
                  else 0
                  end
                ) as warning_unit,
                sum(case when
                  up != '1' || unix_timestamp() - l.dtime > 10*60
                  then 1
                  else 0
                  end         
                ) as failed_unit,
                sum(chain_rateideal_total) as ideal_hashrate,
                sum(chain_rate_total) as current_hashrate,
                avg(case when
                  up != '0'
                  then temp_chips_max
                  else 0
                  end         
                ) as temp_chips_avg,
                max(case when
                  up != '0'
                  then temp_chips_max
                  else 0
                  end         
                ) as temp_chips_max,
                min(case when
                  up != '0'
                  then temp_chips_max
                  end         
                ) as temp_chips_min
            from
              last_stats l
            join 
              miners m on m.id = l.miner_id              
            where 
              m.status = '1' %s
              
        ", $allowed_location_string));

        $sth->execute();

        $row = $sth->fetch(\PDO::FETCH_ASSOC);

        foreach ($row as $attribute => $value) {
            self::$cache[$attribute] = $this->$attribute = $value;
        }

        // Warning and failed units
        $sth = $pdo->query(sprintf("
            select
              l.* 
            from
              last_stats l
              join 
              miners m on m.id = l.miner_id    
            where
              (
                  up is null 
                  || up = '0'
                  || (unix_timestamp() - l.dtime > 60*20) 
                  || (chips_bad > 0)
                  || (chips_lost > 0)
                  || (hw_error_rate  >= 0.097)
                  || (temp_chips_max >= 90)
                  || (temp_board_max >= 80)
                  || (chain_rate_total / chain_rateideal_total <= 0.60)
              )
              and  m.status = '1' %s
              
        ", $allowed_location_string));

        while ($last_stat = $sth->fetchObject('\App\LastStat')) {
            /** @var $last_stat LastStat */
            if ($last_stat->getStatus() === LastStat::STATUS_WARNING) {
                $this->warning_units[] = $last_stat;
            }

            if ($last_stat->getStatus() === LastStat::STATUS_FAILED) {
                $this->failed_units[] = $last_stat;
            }
        }

        self::$cache["warning_units"] = $this->warning_units;
        self::$cache["failed_units"] = $this->failed_units;

    }



    /**
     * Get total_unit
     * @see total_unit
     * @return int
     */
    public function getTotalUnit(): int
    {
        return $this->total_unit;
    }

    /**
     * Set total_unit
     * @see total_unit
     * @param int $total_unit
     * @return Flow
     */
    public function setTotalUnit(int $total_unit): Flow
    {
        $this->total_unit = $total_unit;
        return $this;
    }

    /**
     * Get warning_unit
     * @see warning_unit
     * @return int
     */
    public function getWarningUnit(): int
    {
        return $this->warning_unit;
    }

    /**
     * Set warning_unit
     * @see warning_unit
     * @param int $warning_unit
     * @return Flow
     */
    public function setWarningUnit(int $warning_unit): Flow
    {
        $this->warning_unit = $warning_unit;
        return $this;
    }

    /**
     * Get failed_unit
     * @see failed_unit
     * @return int
     */
    public function getFailedUnit(): int
    {
        return $this->failed_unit;
    }

    /**
     * Set failed_unit
     * @see failed_unit
     * @param int $failed_unit
     * @return Flow
     */
    public function setFailedUnit(int $failed_unit): Flow
    {
        $this->failed_unit = $failed_unit;
        return $this;
    }

    /**
     * Get ideal_hashrate
     * @see ideal_hashrate
     * @return float
     */
    public function getIdealHashrate(): float
    {
        return $this->ideal_hashrate;
    }

    /**
     * Set ideal_hashrate
     * @see ideal_hashrate
     * @param float $ideal_hashrate
     * @return Flow
     */
    public function setIdealHashrate(float $ideal_hashrate): Flow
    {
        $this->ideal_hashrate = $ideal_hashrate;
        return $this;
    }

    /**
     * Get current_hashrate
     * @see current_hashrate
     * @return float
     */
    public function getCurrentHashrate(): float
    {
        return $this->current_hashrate;
    }

    /**
     * Set current_hashrate
     * @see current_hashrate
     * @param float $current_hashrate
     * @return Flow
     */
    public function setCurrentHashrate(float $current_hashrate): Flow
    {
        $this->current_hashrate = $current_hashrate;
        return $this;
    }

    /**
     * Get temp_chips_avg
     * @see temp_chips_avg
     * @return float
     */
    public function getTempChipsAvg(): float
    {
        return $this->temp_chips_avg;
    }

    /**
     * Set temp_chips_avg
     * @see temp_chips_avg
     * @param float $temp_chips_avg
     * @return Flow
     */
    public function setTempChipsAvg(float $temp_chips_avg): Flow
    {
        $this->temp_chips_avg = $temp_chips_avg;
        return $this;
    }

    /**
     * Get temp_chips_max
     * @see temp_chips_max
     * @return int
     */
    public function getTempChipsMax(): int
    {
        return (int)$this->temp_chips_max;
    }

    /**
     * Set temp_chips_max
     * @see temp_chips_max
     * @param int $temp_chips_max
     * @return Flow
     */
    public function setTempChipsMax(int $temp_chips_max): Flow
    {
        $this->temp_chips_max = $temp_chips_max;
        return $this;
    }

    /**
     * Get temp_chips_min
     * @see temp_chips_min
     * @return int
     */
    public function getTempChipMin(): int
    {
        return (int)$this->temp_chips_min;
    }

    /**
     * Set temp_chip_min
     * @see temp_chip_min
     * @param int $temp_chips_min
     * @return Flow
     */
    public function setTempChipsMin(int $temp_chips_min): Flow
    {
        $this->temp_chips_min = $temp_chips_min;
        return $this;
    }

    /**
     * Get warning_units
     * @see warning_units
     * @return LastStat[]
     */
    public function getWarningUnits(): array
    {
        return $this->warning_units;
    }

    /**
     * Set warning_units
     * @see warning_units
     * @param LastStat[] $warning_units
     * @return Flow
     */
    public function setWarningUnits(array $warning_units): Flow
    {
        $this->warning_units = $warning_units;
        return $this;
    }

    /**
     * Get failed_units
     * @see failed_units
     * @return LastStat[]
     */
    public function getFailedUnits(): array
    {
        return $this->failed_units;
    }

    /**
     * Set failed_units
     * @see failed_units
     * @param LastStat[] $failed_units
     * @return Flow
     */
    public function setFailedUnits(array $failed_units): Flow
    {
        $this->failed_units = $failed_units;
        return $this;
    }

        /**
     * @return float
     */
    public function getSuccessUnitsPercent(): float
    {
        if (!$this->getTotalUnit()) {
            return 0.0;
        }

        return (max(0, $this->getTotalUnit() - $this->getWarningUnit() - $this->getFailedUnit())) * 100 / $this->getTotalUnit();
    }

    /**
     * @return float
     */
    public function getWarningUnitsPercent(): float
    {
        if (!$this->getTotalUnit()) {
            return 0.0;
        }

        return $this->getWarningUnit() * 100 / $this->getTotalUnit();
    }

    /**
     * Количество отвалившихся асиков
     * @return float
     */
    public function getFailedUnitsPercent(): float
    {
        if (!$this->getTotalUnit()) {
            return 0.0;
        }

        return $this->getFailedUnit() * 100 / $this->getTotalUnit();
    }

    /**
     * @return float
     */
    public function getEfficiencyHashrate(): float
    {
        if (!$this->getIdealHashrate()) {
            return 0.0;
        }

        return $this->getCurrentHashrate() * 100 / $this->getIdealHashrate();
    }


}