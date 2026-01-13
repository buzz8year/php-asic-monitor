<?php

namespace App\Miners\Views\Html\ConfiguredDevices;

use App\Bootstrap3\Helpers\Elements;
use App\ConfiguredDevice;
use App\Datetime;
use App\LastStat;
use App\Views\HtmlView;
use App\Views\ViewInterface;

/**
 * Class ShowDeiceView
 * @package App\Miners\Views\Html\ConfiguredDevices
 */
class ShowDeiceView extends HtmlView implements ViewInterface
{
    /**
     * @var ConfiguredDevice $conf_device
     */
    protected $conf_device;

    /**
     * @var int $offset
     */
    protected $offset;

    /**
     * @return ConfiguredDevice
     */
    public function getConfDevice(): ConfiguredDevice
    {
        return $this->conf_device;
    }

    /**
     * @param ConfiguredDevice $conf_device
     * @return $this
     */
    public function setConfDevice(ConfiguredDevice $conf_device): ShowDeiceView
    {
        $this->conf_device = $conf_device;
        return $this;
    }

    /**
     * @return int
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * @param int|string $offset
     * @return $this
     */
    public function setOffset($offset): ShowDeiceView
    {
        $this->offset = (int)$offset;
        return $this;
    }

    public function out()
    {
        ?>

        <div class="row">
            <div class="col-xs-12">
                <div class="btn-group pull-right">
                    <a href="/Miners/ConfiguredDevices?offset=<?= abs($this->getOffset()); ?>" class="btn btn-default">
                        Back to devices
                    </a>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-actions">
                    <button data-collapse="#panel-main-data" title="" class="btn-panel"
                            data-original-title="collapse">
                        <i class="fa fa-caret-down"></i>
                    </button>
                </div>
                <h3 class="panel-title">Main info</h3>
            </div>
            <div class="panel-body" id="panel-main-data">
                <?php (new Elements\StaticText())
                    ->setTitle("Record created")
                    ->setValue($this->conf_device->getAddedAt())
                    ->out();
                ?>

                <?php (new Elements\StaticText())
                    ->setTitle("Worker Name")
                    ->setValue($this->conf_device->getWorkerName())
                    ->out();
                ?>

                <?php (new Elements\StaticText())
                    ->setTitle("IP address")
                    ->setValue($this->conf_device->getIpAddress())
                    ->out();
                ?>

                <?php (new Elements\StaticText())
                    ->setTitle("MAC address")
                    ->setValue($this->conf_device->getMacAddress())
                    ->out();
                ?>

                <?php (new Elements\StaticText())
                    ->setTitle("Configuration was used")
                    ->setValue($this->conf_device->getWasUsed() ? "Yes" : "No")
                    ->out();
                ?>

                <?php if (!sizeof($this->conf_device->getSimilarDevices())): ?>
                    <div class="callout callout-info">
                        Hey, looks like this machine was configured for the first time or at least it wasn't added to
                        the monitoring system!<br/>
                        May be, we should <a
                                href="/Miners/ConfiguredDevices/CreateFrom/<?= $this->conf_device->getId(); ?>/">add</a>
                        it?
                    </div>
                <?php else: ?>
                    <div class="callout callout-info">
                        Looks like, this device was already added to the system at least once
                        May be, we should <a
                                href="/Miners/ConfiguredDevices/CreateFrom/<?= $this->conf_device->getId(); ?>/">add</a>
                        it?<br/>
                        Or edit one of miners below?
                    </div>
                    <h3>List of miners</h3>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>ID (link)</th>
                                <th>Pool (Stratum)</th>
                                <th>Worker Name</th>
                                <th>Intranet IP</th>
                                <th class="text-muted">Port</th>
                                <th>MAC</th>
                                <th>Model</th>
                                <th>Location</th>
                                <!-- <th>Name</th> -->
                                <th class="text-muted">Description</th>
                                <th>Add datetime</th>
                                <th>Enabled</th>
                                <th>State</th>
                                <th class="text-right">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($this->conf_device->getSimilarDevices() as $miner):?>
                                <tr>
                                    <td><a class="btn btn-xs btn-default" href="/RealTime/FullData/<?= $miner->getId(); ?>"><?= $miner->getId(); ?></td>
                                    <!-- <td><?php //print $miner->getPool()->getStratumUrl(); ?></td> -->
                                    <td><?= $miner->getPool()->getUrl(); ?></td>
                                    <td class="text-muted" style="cursor: help" title="<?= $miner->getPool()->getWorker(); ?>">
                                        <small>
                                            <?= substr($miner->getPool()->getWorker(), 0, 20) . (strlen($miner->getPool()->getWorker()) > 20 ? '...' : ''); ?>
                                        </small>
                                    </td>
                                    <td><?= $miner->getIp();?></td>
                                    <td class="text-muted"><?= $miner->getPort();?></td>
                                    <td><?= $miner->getMac();?></td>
                                    <td class="text-muted">
                                        <?= $miner->getModel()->getName();?>
                                        <small>
                                            / <?= $miner->getModel()->getDescription(); ?>
                                        </small>
                                    </td>
                                    <td><?= $miner->getLocation()->getName();?></td>
                                    <!-- <td><?php //print $miner->getName(); ?></td> -->
                                    <td class="text-muted" style="cursor: help" title="<?= $miner->getDescription(); ?>">
                                        <small>
                                            <?= substr($miner->getDescription(), 0, 20) . (strlen($miner->getDescription()) > 20 ? '...' : ''); ?>
                                        </small>
                                    </td>
                                    <td><?= Datetime::create("@" . $miner->getDtime(), "UTC")->format("m/d/Y H:i:s");?></td>
                                    <td>
                                        <?php if ($miner->getStatus()):;?>
                                            <i class="text-success fa fa-check" title="Active" data-toggle="tooltip"></i>
                                        <?php else: ?>
                                            <i class="text-danger fa fa-times" title="Inactive" data-toggle="tooltip"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        switch ($miner->getLastStat()->getStatus()):
                                            case LastStat::STATUS_OK:
                                                print '<label class="label label-success" title="Unit up and work properly" data-toggle="tooltip">OK</label>';
                                                break;

                                            case LastStat::STATUS_WARNING:
                                                $warnings = implode("<br>", $miner->getLastStat()->getWarnings());
                                                print '<label class="label label-warning" data-html="true" title="' . $warnings . '" data-toggle="tooltip">WARNING</label>';
                                                break;

                                            case LastStat::STATUS_FAILED:
                                                print '<label class="label label-danger" title="Unit down" data-toggle="tooltip">DOWN</label>';
                                                break;

                                            default:
                                                trigger_error(sprintf("Unknown status %u", $miner->getLastStat()->getStatus()), E_USER_WARNING);

                                        endswitch;

                                        ?>
                                    </td>
                                    <td class="text-right">
                                        <a href="/Miners/ConfiguredDevices/EditFrom/<?=$this->conf_device->getId();?>/<?= $miner->getId();?>/" title="Edit device with current config" data-toggle="tooltip"><i class="fa fa-lg fa-edit"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-actions">
                    <button data-collapse="#panel-configs-data" title="" class="btn-panel"
                            data-original-title="collapse">
                        <i class="fa fa-caret-down"></i>
                    </button>
                </div>
                <h3 class="panel-title">Configs string</h3>
            </div>
            <div class="panel-body" id="panel-configs-data">
                <?php (new Elements\StaticText())
                    ->setTitle("Uploaded configs")
                    ->setValue("<pre>" . $this->conf_device->getConfiguration() . "</pre>")
                    ->out();
                ?>
            </div>
        </div>

        <?php
    }
}