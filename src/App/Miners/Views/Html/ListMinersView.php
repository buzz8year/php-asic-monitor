<?php
/**

 * Date: 31.05.2018
 * Time: 19:40
 */

namespace App\Miners\Views\Html;


use App\Db\PDOFactory;
use App\LastStat;
use App\Miner;
use App\Datetime;
use App\Miners\MinersSummaryInfo;
use App\UserAuth;
use App\Views\HtmlView;
use App\Views\ViewInterface;

class ListMinersView extends HtmlView implements ViewInterface
{
    /**
     * Майнеры для отображения
     * @var Miner[]
     */
    protected $miners = array();
    /**
     * Класс для работы со сводной информацией о майнерах
     * @var MinersSummaryInfo
     */
    protected $miners_summary_info;
    /**
     * Признак, необходимо ли отображать ссылка ну отображение неактивных майнеров
     * @var bool
     */
    protected $show_inactive_link = false;

    /**
     * Возвращает miners
     * @see miners
     * @return Miner[]
     */
    public function getMiners(): array
    {
        return $this->miners;
    }

    /**
     * Устанавливает miners
     * @see miners
     * @param Miner[] $miners
     * @return ListMinersView
     */
    public function setMiners(array $miners): ListMinersView
    {
        $this->miners = $miners;
        return $this;
    }

    /**
     * Возвращает miners_summary_info
     * @see miners_summary_info
     * @return MinersSummaryInfo
     */
    public function getMinersSummaryInfo(): MinersSummaryInfo
    {
        return $this->miners_summary_info ?? new MinersSummaryInfo(UserAuth::getAuthenticatedUser(), PDOFactory::getReadPDOInstance());
    }

    /**
     * Устанавливает miners_summary_info
     * @see miners_summary_info
     * @param MinersSummaryInfo $miners_summary_info
     * @return ListMinersView
     */
    public function setMinersSummaryInfo(MinersSummaryInfo $miners_summary_info): ListMinersView
    {
        $this->miners_summary_info = $miners_summary_info;
        return $this;
    }

    /**
     * Возвращает show_inactive_link
     * @see show_inactive_link
     * @return bool
     */
    public function isShowInactiveLink(): bool
    {
        return $this->show_inactive_link;
    }

    /**
     * Устанавливает show_inactive_link
     * @see show_inactive_link
     * @param bool $show_inactive_link
     * @return ListMinersView
     */
    public function setShowInactiveLink(bool $show_inactive_link): ListMinersView
    {
        $this->show_inactive_link = $show_inactive_link;
        return $this;
    }


    /**
     * Возвращает locations
     * @see locations
     * @return Location[]
     */
    public function getLocations(): array
    {
        return $this->locations;
    }

    /**
     * Устанавливает locations
     * @see locations
     * @param Location[] $locations
     * @return ListMinersView
     */
    public function setLocations(array $locations): ListMinersView
    {
        $this->locations = $locations;
        return $this;
    }



    /**
     * @inheritdoc
     */
    public function out()
    {
        ?>

        <div class="row">
            <div class="col-xs-5">
                <!-- <h4>Summary info:</h4> -->
                <p>Total miners (unit): <span class="text-bold"><?php print $this->getMinersSummaryInfo()->getTotalMiners(); ?></span></p>
                <p>Active mines (unit): <span class="text-bold"><?php print $this->getMinersSummaryInfo()->getActiveMinters(); ?></span></p>
                <p>Inactive mines (unit): <span class="text-bold"><?php print $this->getMinersSummaryInfo()->getInactiveMiners(); ?></span></p>
            </div>

            <div class="col-xs-7">

                <div class="pull-left">
                    <span class="btn btn-link">Locations: </span>
                    <?php foreach ($this->getLocations() as $location) : ?>
                        <a href="/Miners/Location/<?= $location->getId() ?>" class="btn btn-default">
                            <?= $location->getName() ?> : <?= $location->countMiners() ?>
                        </a> 
                    <?php endforeach; ?>
                </div>

                <div class="pull-right">
                    <a class="btn btn-success" href="/Miners/Add">Add Unit</a>
                    <?php if (!$this->show_inactive_link):?>
                        <a class="btn btn-success" href="/Miners">Show active units</a>
                    <?php else: ?>
                        <a class="btn btn-danger" href="/Miners/Inactive">Show inactive units</a>
                    <?php endif; ?>
                    <a class="btn btn-default" href="/Miners/ConfiguredDevices">Configured devices</a>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <?php if (sizeof($this->miners)):?>
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
                    <?php foreach ($this->miners as $miner):?>
                        <tr>
                            <td><a class="btn btn-xs btn-default" href="/RealTime/FullData/<?php print $miner->getId(); ?>"><?php print $miner->getId(); ?></td>
                            <!-- <td><?php //print $miner->getPool()->getStratumUrl(); ?></td> -->
                            <td><?php print $miner->getPool()->getUrl(); ?></td>
                            <td class="text-muted" style="cursor: help" title="<?php print $miner->getPool()->getWorker(); ?>">
                                <small>
                                    <?php print substr($miner->getPool()->getWorker(), 0, 20) . (strlen($miner->getPool()->getWorker()) > 20 ? '...' : ''); ?>
                                </small>
                            </td>
                            <td><?php print $miner->getIp();?></td>
                            <td class="text-muted"><?php print $miner->getPort();?></td>
                            <td><?php print $miner->getMac();?></td>
                            <td class="text-muted">
                                <?php print $miner->getModel()->getName();?>
                                <small>
                                    / <?php print $miner->getModel()->getDescription(); ?>
                                </small>
                            </td>
                            <td><?php print $miner->getLocation()->getName();?></td>
                            <!-- <td><?php //print $miner->getName(); ?></td> -->
                            <td class="text-muted" style="cursor: help" title="<?php print $miner->getDescription(); ?>">
                                <small>
                                    <?php print substr($miner->getDescription(), 0, 20) . (strlen($miner->getDescription()) > 20 ? '...' : ''); ?>
                                </small>
                            </td>
                            <td><?php print Datetime::create("@" . $miner->getDtime(), "UTC")->format("m/d/Y H:i:s");?></td>
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
                                <a href="/Miners/Edit/<?php print $miner->getId();?>" title="Edit" data-toggle="tooltip"><i class="fa fa-lg fa-edit"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
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