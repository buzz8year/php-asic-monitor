<?php
/**

 * Date: 31.05.2018
 * Time: 22:07
 */

namespace App\Miners;


use App\User;

class MinersSummaryInfo
{
    /**
     * Сколько всего майнеров
     * @var int
     */
    protected $total_miners = 0;
    /**
     * Количество активных майнеров
     * @var int
     */
    protected $active_minters = 0;
    /**
     * Количество неактивных майнеров
     * @var int
     */
    protected $inactive_miners = 0;

    /**
     * MinersSummary constructor.
     * @param User $user
     * @param \PDO $dbh
     */
    public function __construct(User $user, \PDO $dbh)
    {
        if (!sizeof($user->getAllowedLocations())) {
            return;
        }

        $allowed_location = $user->getAllowedLocations();
        array_walk($allowed_location, "intval");
        $allowed_location_string = "'".(implode("', '", $allowed_location))."'";

        $sth = $dbh->query(sprintf("
            select count(*) as cnt from miners where allocation_id in (%s)
            union all 
            select count(*) as cnt from miners where status = '1' && allocation_id in (%s)
        ",
            $allowed_location_string,
            $allowed_location_string
            ));

        $this->total_miners = $sth->fetch(\PDO::FETCH_ASSOC)['cnt'];
        $this->active_minters = $sth->fetch(\PDO::FETCH_ASSOC)['cnt'];
        $this->inactive_miners = $this->total_miners - $this->active_minters;
    }

    /**
     * Возвращает total_miners
     * @see total_miners
     * @return int
     */
    public function getTotalMiners(): int
    {
        return $this->total_miners;
    }

    /**
     * Возвращает active_minters
     * @see active_minters
     * @return int
     */
    public function getActiveMinters(): int
    {
        return $this->active_minters;
    }

    /**
     * Возвращает inactive_miners
     * @see inactive_miners
     * @return int
     */
    public function getInactiveMiners(): int
    {
        return $this->inactive_miners;
    }
}