<?php

namespace App\RealTime\Views\Html;

use App\Datetime;
use App\LastStat;
use App\RealTime\Flow;
use App\Views\BaseView;
use App\Views\ViewInterface;

class UnitsDetailsView extends BaseView implements ViewInterface
{
    /**
     * @var LastStat[]
     */
    protected $last_stats = array();
    /**
     * @var Flow
     */
    protected $flow;

    /**
     * @see last_stats
     * @return LastStat[]
     */
    public function getLastStats(): array
    {
        return $this->last_stats;
    }

    /**
     * @see last_stats
     * @param LastStat[] $last_stats
     * @return UnitsDetailsView
     */
    public function setLastStats(array $last_stats): UnitsDetailsView
    {
        $this->last_stats = $last_stats;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function out()
    {
    ?>

        <?php if (sizeof($this->getLastStats())):?>
        <h3>Unit details</h3>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Location</th>
                        <th>IP</th>
                        <th>Last scanned at</th>
                        <th>Type</th>
                        <th>Hashrate (5 sec)</th>
                        <th>Hashrate (AVG)</th>
                        <th>FAN speed</th>
                        <th>Board count</th>
                        <th>Chips (active)</th>
                        <th>Hashrate (total)</th>
                        <th>Hashrate (total ideal)</th>
                        <th>HW error rate</th>
                        <th>Max temp on chips (&#8451;)</th>
                        <th>Max temp on board (&#8451;)</th>
                        <th>State</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <?php foreach ($this->getLastStats() as $stat):?>
                        <tr>
                            <td><?php print $stat->getMinerId();?></td>
                            <td><?php print $stat->getMiner()->getLocation()->getName();?></td>
                            <td><?php print $stat->getMiner()->getIp();?></td>
                            <td style="white-space: nowrap">
                                <?php print Datetime::create("@" . $stat->getDtime(), "UTC")->format("d/m/Y H:i:s");?>
                                <br><span class="small text-muted">(updated <?php print (time() - (int)$stat->getDtime());?> second ago)</span>
                            </td>
                            <td style="white-space: nowrap"><?php print $stat->getType();?></td>
                            <td><?php print $stat->getHashrate() / 1000;?></td>
                            <td><?php print $stat->getHashrateAvg() / 1000;?></td>
                            <td><?php print $stat->getFanSpeed();?></td>
                            <td><?php print $stat->getMinerCount();?></td>
                            <td><?php print $stat->getChipsAlive();?></td>
                            <td><?php print $stat->getChainRateTotal() / 1000;?></td>
                            <td><?php print $stat->getChainRateidealTotal() / 1000;?></td>
                            <td><?php print $stat->getHwErrorRate();?></td>
                            <td><?php print $stat->getTempChipsMax();?></td>
                            <td><?php print $stat->getTempBoardMax();?></td>
                            <td>
                                <?php
                                switch ($stat->getStatus()):
                                    case LastStat::STATUS_OK:
                                        print '<label class="label label-success" title="Unit up and work properly" data-toggle="tooltip">OK</label>';
                                        break;

                                    case LastStat::STATUS_WARNING:
                                        $warnings = implode("<br>", $stat->getWarnings());
                                        print '<label class="label label-warning" data-html="true" title="'.$warnings.'" data-toggle="tooltip">WARNING</label>';
                                        break;

                                    case LastStat::STATUS_FAILED:
                                        print '<label class="label label-danger" title="Unit down" data-toggle="tooltip">DOWN</label>';
                                        break;

                                    default:
                                        trigger_error(sprintf("Unknown status %u", $stat->getStatus()), E_USER_WARNING);

                                    endswitch;

                                ?>
                            </td>
                            <td>
                                <a href="/RealTime/FullData/<?php print $stat->getMinerId();?>"><i class="fa fa-lg fa-info-circle" title="Full information" data-toggle="tooltip"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php else: ?>
            <div class="callout callout-danger">
                <h4>Oooopppss...</h4>
                Miners not found
            </div>
        <?php endif; ?>

    <?php
    }
}