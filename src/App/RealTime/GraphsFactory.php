<?php

namespace App\RealTime;

use App\Datetime;
use App\Db\PDOFactory;
use App\LineGraphs\LineGraph;
use App\LineGraphs\LineGraphDataRow;
use App\Locations\GetAllLocations;
use App\Locations\GetLocations4User;
use App\User;

class GraphsFactory
{
    /**
     * @var LineGraph[]
     */
    protected $graphs = array();
    /**
     * @var User
     */
    private $user;

    /**
     * GraphsFactory constructor.
     * @param User|null $user
     */
    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    /**
     * @return LineGraph[]
     */
    public function getGraphs()
    {
        if (sizeof($this->graphs)) {
            return $this->graphs;
        }

        if (isset($this->user)) {

            if (!sizeof($this->user->getAllowedLocations())) {
                return array();
            }

            $allowed_location_ids = $this->user->getAllowedLocations();
            array_walk($allowed_location_ids, "intval");
            $allowed_location_string = " && miners.allocation_id in ('".(implode("', '", $allowed_location_ids))."')";
        } else {
            $allowed_location_string = "";
        }

        $pdo = PDOFactory::getReadPDOInstance();

        if (!isset($this->user)) {
            $locations = (new GetAllLocations())->getLocations($pdo);
        } else {
            $locations = (new GetLocations4User($this->user))->getLocations($pdo);
        }

        $sth = $pdo->prepare(sprintf("
            select 
              miners.allocation_id as location_id,
              count(distinct journal.miner_id) as cnt,
              year,
              month,
              day,
              hour
            from
              journal
            join 
              miners on miners.id = journal.miner_id
            where
               journal.up = '1' && journal.dtime >= :time %s
            group by miners.allocation_id, year, month, day, hour
               
        ", $allowed_location_string));

        $index = array();
        $sth->execute(array(
            ":time" => time() - 60*60*24
        ));

        while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
            $datetime = Datetime::create(sprintf("%u-%02u-%02u %02u:%02u:%02u", $row['year'], $row['month'], $row['day'], $row['hour'], 0, 0), "UTC");
            $index[$datetime->getTimestamp()][$row['location_id']] = $row;
        }

        // Hashrate
        $graph = new LineGraph();
        $graph
            ->setTitle(sprintf("Active Units"))
            ->setLineColors(array('#13A89E', '#95a5a6'))
            ->setXkey("period")
            ->setXLabels("hours")
        ;

        foreach ($index as $timestamp => $avg_in_location) {
            $datetime = Datetime::create("@". $timestamp, "UTC");
            $data_row = new LineGraphDataRow();
            $data_row->setPeriod($datetime->format("Y-m-d H:i:s"));
            foreach ($locations as $location) {
                $data_row->addEntry(
                    $location->getName(),
                    "location_" . $location->getId(),
                    (float)sprintf("%u", ($avg_in_location[$location->getId()]['cnt'] ?? 0))
                );
            }
            $graph->addDataRow($data_row);
        }

        $this->graphs[] = $graph;

        /*
         * temp graphs
         */

        foreach ($locations as $location) {
            $sth = $pdo->prepare("
            select
              max(temp_chips_max) as temp_chips_max,
              avg(temp_chips_max) as temp_chips_avg,
              min(temp_chips_max) as temp_chips_min,

              max(temp_board_max) as temp_board_max,
              avg(temp_board_max) as temp_board_avg,
              min(temp_board_max) as temp_board_min,
              
              year,
              month,
              day,
              hour
            from
              journal
            join 
              miners on miners.id = journal.miner_id
            where
               journal.dtime >= :time && miners.allocation_id = :location_id
            group by year, month, day, hour
            ");

            $sth->execute(array(
                ":time" => time() - 60*60*24,
                ":location_id" => $location->getId()
            ));

            // Chips temp graph
            $graph = new LineGraph();
            $graph
                ->setTitle(sprintf("Chips Temperature at %s", $location->getName()))
                ->setLineColors(array('#f44242', '#e07f1f', '#c9c914'))
                ->setXkey("period")
                ->setXLabels("hours")
            ;

            // Board temp graph
            $graph2 = new LineGraph();
            $graph2
                ->setTitle(sprintf("Board Temperature at %s", $location->getName()))
                ->setLineColors(array('#f44242', '#e07f1f', '#c9c914'))
                ->setXkey("period")
                ->setXLabels("hours")
            ;

            while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {

                $datetime = Datetime::create(sprintf("%u-%02u-%02u %02u:%02u:%02u", $row['year'], $row['month'], $row['day'], $row['hour'], 0, 0), "UTC");

                $data_row = new LineGraphDataRow();
                $data_row->setPeriod($datetime->format("Y-m-d H:i:s"));
                $data_row->addEntry("Max", "temp_chips_max", (float)$row['temp_chips_max']);
                $data_row->addEntry("Avg", "temp_chips_avg", (float)$row['temp_chips_avg']);
                $data_row->addEntry("Min", "temp_chips_min", (float)$row['temp_chips_min']);
                $graph->addDataRow($data_row);

                $data_row = new LineGraphDataRow();
                $data_row->setPeriod($datetime->format("Y-m-d H:i:s"));
                $data_row->addEntry("Max", "temp_board_max", (float)$row['temp_board_max']);
                $data_row->addEntry("Avg", "temp_board_avg", (float)$row['temp_board_avg']);
                $data_row->addEntry("Min", "temp_board_min", (float)$row['temp_board_min']);
                $graph2->addDataRow($data_row);
            };

            $this->graphs[] = $graph;
            $this->graphs[] = $graph2;

        }

        return $this->graphs;
    }
}