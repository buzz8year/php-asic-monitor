<?php

namespace App\Miners\Views\Html\ConfiguredDevices;

use App\Bootstrap3\Helpers\Pager;
use App\ConfiguredDevice;
use App\Views\{
    HtmlView, ViewInterface
};

/**
 * Class IndexView
 * @package App\Miners\Views\Html\ConfiguredDevices
 */
class IndexView extends HtmlView implements ViewInterface
{
    /**
     * @var ConfiguredDevice[] $devices
     */
    protected $devices;

    /**
     * @var Pager $pager
     */
    protected $pager;

    /**
     * @return ConfiguredDevice[]
     */
    public function getDevices(): ?array
    {
        return $this->devices;
    }

    /**
     * @param ConfiguredDevice[] $devices
     * @return $this
     */
    public function setDevices($devices): IndexView
    {
        $this->devices = $devices;
        return $this;
    }

    /**
     * @return Pager
     */
    public function getPager(): Pager
    {
        return $this->pager;
    }

    /**
     * @param Pager $pager
     * @return $this
     */
    public function setPager(Pager $pager): IndexView
    {
        $this->pager = $pager;
        return $this;
    }

    public function out()
    {
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-actions">
                    <button data-collapse="#panel-main-data" title="" class="btn-panel"
                            data-original-title="collapse">
                        <i class="fa fa-caret-down"></i>
                    </button>
                </div>
                <h3 class="panel-title">Reconfigured devices</h3>
            </div>
            <div class="panel-body" id="panel-main-data">
                <?php if (!$this->getDevices() && (!isset($this->pager) || $this->pager->getPagesCount() < 1)): ?>
                    <div class="callout callout-warning">
                        <h4>Ooops</h4>
                        There is no information about reconfigured devices in the system
                    </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <td>Record ID</td>
                                <td>Added at</td>
                                <td>Worker name</td>
                                <td>IP address</td>
                                <td>MAC address</td>
                                <td>Location</td>
                                <td>Unit updated</td>
                                <td>Actions</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($this->getDevices() as $device): ?>
                                <tr>
                                    <td><?= $device->getId(); ?></td>
                                    <td><?= $device->getAddedAt(); ?></td>
                                    <td><?= $device->getWorkerName(); ?></td>
                                    <td><?= $device->getIpAddress(); ?></td>
                                    <td><?= $device->getMacAddress(); ?></td>
                                    <td><?= $device->getLocation()->getName() ?: "Unknown"; ?></td>
                                    <td>
                                        <?php if($device->getWasUsed()):?>
                                            <span style="color: green"><i class="fa fa-check"></i></span>&nbsp;Yes
                                        <?php else:?>
                                            <span style="color: red"><i class="fa fa-close"></i></span>&nbsp;No
                                        <?php endif;?>
                                    </td>
                                    <td>
                                        <a href="/Miners/ConfiguredDevices/View/<?= $device->getId(); ?>">
                                            <i class="fa fa-eye"></i>&nbsp;View
                                        </a>
                                        <br/>
                                        <a href="/Miners/ConfiguredDevices/Delete/<?= $device->getId(); ?>"
                                           onclick="return confirm('Are you sure that you want to delete this device? This action is irreversible!');">
                                            <i class="fa fa-trash"></i>&nbsp;Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="clearfix"></div>
                <?php if (isset($this->pager) && $this->pager->getPagesCount() > 1): ?>
                    <?php $this->pager->out(); ?>
                <?php endif; ?>
            </div>
        </div>

    <?php endif; ?>
        <?php
    }
}