<?php
/**

 * Date: 31.05.2018
 * Time: 20:12
 */

namespace App\Miners;


class GetActiveMiners implements GetMinersInterface
{
    /**
     * @inheritDoc
     */
    public function getMiners(\PDO $pdo)
    {
        $sth = $pdo->query("select * from miners where status = '1' order by ip");
        return $sth->fetchAll(\PDO::FETCH_CLASS, '\App\Miner');
    }

}