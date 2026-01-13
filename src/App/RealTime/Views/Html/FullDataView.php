<?php

namespace App\RealTime\Views\Html;

use App\Bootstrap3\Helpers\ResultError;
use App\Bootstrap3\Helpers\Elements\StaticText;
use App\Datetime;
use App\LastStat;
use App\Miner;
use App\Views\HtmlView;
use App\Views\ViewInterface;

class FullDataView extends HtmlView implements ViewInterface
{
    /**
     * @var Miner
     */
    protected $miner;
    /**
     * @var LastStat
     */
    protected $last_stat;

    /**
     * @see miner
     * @return Miner
     */
    public function getMiner(): Miner
    {
        return $this->miner;
    }

    /**
     * @see miner
     * @param Miner $miner
     * @return FullDataView
     */
    public function setMiner(Miner $miner): FullDataView
    {
        $this->miner = $miner;
        return $this;
    }

    /**
     * @see last_stat
     * @return LastStat
     */
    public function getLastStat(): LastStat
    {
        return $this->last_stat;
    }

    /**
     * @see last_stat
     * @param LastStat $last_stat
     * @return FullDataView
     */
    public function setLastStat(LastStat $last_stat): FullDataView
    {
        $this->last_stat = $last_stat;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function out()
    {
        ?>

        <?php if ($this->getResult()->hasErrors()): ?>
            <?php (new ResultError($this->getResult()))->out();?>
        <?php else: ?>
        <h3>Last statistic data for ASIC with ip <?php print $this->getMiner()->getIp(); ?></h3>
        <form class="form-horizontal">

            <?php if ($this->getLastStat()->getWarnings()):?>
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">Unit warnings</h3>
                    </div>
                    <div class="panel-body">
                        <ul>
                            <li><?php print implode("</li><li>", $this->getLastStat()->getWarnings()); ?></li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Unit configuration</h3>
                </div>
                <div class="panel-body">
                    <?php

                    (new StaticText())
                        ->setTitle("ID")
                        ->setValue($this->getMiner()->getId())
                        ->out();

                    (new StaticText())
                        ->setTitle("IP")
                        ->setValue($this->getMiner()->getIp())
                        ->out();

                    (new StaticText())
                        ->setTitle("Port")
                        ->setValue($this->getMiner()->getPort())
                        ->out();

                    (new StaticText())
                        ->setTitle("MAC")
                        ->setValue($this->getMiner()->getMac())
                        ->out();

                    (new StaticText())
                        ->setTitle("Model")
                        ->setValue($this->getMiner()->getModel()->getDescription())
                        ->out();

                    (new StaticText())
                        ->setTitle("Name")
                        ->setValue($this->getMiner()->getName())
                        ->out();

                    (new StaticText())
                        ->setTitle("Description")
                        ->setValue($this->getMiner()->getDescription())
                        ->out();

                    (new StaticText())
                        ->setTitle("Setup")
                        ->setValue(Datetime::create("@" . $this->getMiner()->getDtime(), "UTC")->format("m/d/Y H:i:s"))
                        ->out();

                    (new StaticText())
                        ->setTitle("Active")
                        ->setValue($this->getMiner()->getStatus() ? "Yes" : "No")
                        ->out();
                    ?>

                    <div class="row">
                        <div class="col-xs-12">
                            <a class="btn btn-success pull-right" href="/Miners/Edit/<?php print $this->getMiner()->getId();?>"><i class="fa fa-edit"></i> Edit</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Unit last state</h3>
                </div>
                <div class="panel-body">
                    <?php

                    (new StaticText())
                        ->setTitle("Unique ID")
                        ->setValue($this->getLastStat()->getUniqueId())
                        ->out();

                    (new StaticText())
                        ->setTitle("UP")
                        ->setValue($this->getLastStat()->getUp() ? "Yes" : "No")
                        ->out();

                    (new StaticText())
                        ->setTitle("Last updated at")
                        ->setValue(Datetime::create("@" . $this->getLastStat()->getDtime())->format("m/d/Y H:i:s"))
                        ->out();

                    (new StaticText())
                        ->setTitle("Last updated ago")
                        ->setValue(Datetime::elapse_datetime_format((time() - $this->getLastStat()->getDtime())))
                        ->out();

                    (new StaticText())
                        ->setTitle("Uptime")
                        ->setValue(Datetime::elapse_datetime_format($this->getLastStat()->getUptime()))
                        ->out();

                    (new StaticText())
                        ->setTitle("Type")
                        ->setValue($this->getLastStat()->getType())
                        ->out();

                    (new StaticText())
                        ->setTitle("Version")
                        ->setValue($this->getLastStat()->getBmminer())
                        ->out();

                    (new StaticText())
                        ->setTitle("Hardware version")
                        ->setValue($this->getLastStat()->getHardware())
                        ->out();

                    (new StaticText())
                        ->setTitle("Firmware version")
                        ->setValue($this->getLastStat()->getFirmware())
                        ->out();

                    (new StaticText())
                        ->setTitle("Model")
                        ->setValue($this->getLastStat()->getModel())
                        ->out();

                    (new StaticText())
                        ->setTitle("Hashrate (AVG in 5 sec)")
                        ->setValue($this->getLastStat()->getHashrate())
                        ->out();

                    (new StaticText())
                        ->setTitle("Hashrate (AVG while uptime)")
                        ->setValue($this->getLastStat()->getHashrateAvg())
                        ->out();

                    (new StaticText())
                        ->setTitle("Board's frequency (AVG)")
                        ->setValue($this->getLastStat()->getFreqAvg())
                        ->out();

                    (new StaticText())
                        ->setTitle("Board frequency (total)")
                        ->setValue($this->getLastStat()->getFreqTotal())
                        ->out();

                    (new StaticText())
                        ->setTitle("Board count")
                        ->setValue($this->getLastStat()->getMinerCount())
                        ->out();

                    (new StaticText())
                        ->setTitle("Fan numbers")
                        ->setValue($this->getLastStat()->getFanNum())
                        ->out();

                    (new StaticText())
                        ->setTitle("Fan speed")
                        ->setValue($this->getLastStat()->getFanSpeed())
                        ->out();

                    (new StaticText())
                        ->setTitle("Chips on boards")
                        ->setValue($this->getLastStat()->getChips())
                        ->out();

                    (new StaticText())
                        ->setTitle("Alive chips")
                        ->setValue($this->getLastStat()->getChipsAlive())
                        ->out();

                    (new StaticText())
                        ->setTitle("Bad chips")
                        ->setValue($this->getLastStat()->getChipsBad())
                        ->out();

                    (new StaticText())
                        ->setTitle("Lost chips")
                        ->setValue($this->getLastStat()->getChipsLost())
                        ->out();

                    (new StaticText())
                        ->setTitle("Current chain rate (current chain hashrate)")
                        ->setValue($this->getLastStat()->getChainRate())
                        ->out();

                    (new StaticText())
                        ->setTitle("Total chain rate of all boards")
                        ->setValue($this->getLastStat()->getChainRateTotal())
                        ->out();

                    (new StaticText())
                        ->setTitle("Ideal rate each boards")
                        ->setValue($this->getLastStat()->getChainRateideal())
                        ->out();

                    (new StaticText())
                        ->setTitle("Ideal rate all boards")
                        ->setValue($this->getLastStat()->getChainRateidealTotal())
                        ->out();

                    (new StaticText())
                        ->setTitle("Chain offset")
                        ->setValue($this->getLastStat()->getChainOffset())
                        ->out();

                    (new StaticText())
                        ->setTitle("HW error rate (percent)")
                        ->setValue($this->getLastStat()->getHwErrorRate())
                        ->out();

                    (new StaticText())
                        ->setTitle("Chain HW errors on each board")
                        ->setValue($this->getLastStat()->getChainHw())
                        ->out();

                    (new StaticText())
                        ->setTitle("Chain XTime (may bee time to encrypt block)")
                        ->setValue($this->getLastStat()->getChainXtime())
                        ->out();

                    (new StaticText())
                        ->setTitle("Chips temp for each board")
                        ->setValue($this->getLastStat()->getTempChips())
                        ->out();

                    (new StaticText())
                        ->setTitle("Highest chip temp")
                        ->setValue($this->getLastStat()->getTempChipsMax())
                        ->out();

                    (new StaticText())
                        ->setTitle("Boards temp")
                        ->setValue($this->getLastStat()->getTempBoards())
                        ->out();

                    (new StaticText())
                        ->setTitle("Highest board temp")
                        ->setValue($this->getLastStat()->getTempBoardMax())
                        ->out();

                    ?>

                    <div class="row">
                        <div class="col-xs-12">
                            <a class="btn btn-success pull-right" href="/Journal/Show/<?php print $this->getMiner()->getId();?>"> Log (@todo)</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>


        <?php
    }
}