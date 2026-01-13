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
use App\Views\HtmlView;
use App\Views\ViewInterface;
use App\Location;

class LocationMinersView extends HtmlView implements ViewInterface
{
    /**
     * Майнеры для отображения
     * @var Miner[]
     */
    protected $miners = array();

    /**
     * Локации для Select
     * @var Location[]
     */
    protected $locations = array();

    protected $curLocID;

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
     * @return LocationMinersView
     */
    public function setMiners(array $miners): LocationMinersView
    {
        $this->miners = $miners;
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
     * @return LocationMinersView
     */
    public function setLocations(array $locations): LocationMinersView
    {
        $this->locations = $locations;
        return $this;
    }



    /**
     * Returns current location id
     * @return $curLocID
     */
    public function getCurrentLocationID(): int
    {
        return $this->curLocID;
    }


    /**
     * Устанавливает current location
     * @param $locationID
     * @return LocationMinersView
     */
    public function setCurrentLocationID(int $locationID): LocationMinersView
    {
        $this->curLocID = $locationID;
        return $this;
    }


    /**
     * @inheritdoc
     */
    public function out()
    {
        ?>

        <?php foreach ($this->getLocations() as $location) : ?>
            <a href="/Miners/Location/<?= $location->getId() ?>" class="btn btn-<?= ($this->getCurrentLocationID() == $location->getId() ? 'info' : 'default') ?>">
                <?= $location->getName() ?> : <?= $location->countMiners() ?>
            </a> 
        <?php endforeach; ?>

        <div class="pull-right">
            <a class="btn btn-success" href="/Miners/Add">Add Unit</a>
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
                   <th>IP</th>
                   <th>Port</th>
                   <th>MAC</th>
                   <th>Model</th>
                   <th>Location</th>
                   <th>Name</th>
                   <th>Description</th>
                   <th>Add datetime</th>
                   <th>Enabled</th>
                   <th>State</th>
                   <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($this->miners as $miner):?>
                        <tr>
                            <td><a class="btn btn-xs btn-default" href="/RealTime/FullData/<?php print $miner->getId(); ?>"><?php print $miner->getId(); ?></td>
                            <td><?php print $miner->getPool()->getStratumUrl(); ?></td>
                            <td class="text-muted" style="cursor: help" title="<?php print $miner->getPool()->getWorker(); ?>">
                                <small>
                                    <?php print substr($miner->getPool()->getWorker(), 0, 20) . (strlen($miner->getPool()->getWorker()) > 20 ? '...' : ''); ?>
                                </small>
                            </td>
                            <td><?php print $miner->getIp();?></td>
                            <td class="text-muted"><?php print $miner->getPort();?></td>
                            <td><?php print $miner->getMac();?></td>
                            <td>
                                <?php print $miner->getModel()->getName();?>
                                <span class="text-muted">
                                    (<?php print $miner->getModel()->getDescription();?>)
                                </span>
                            </td>
                            <td><?php print $miner->getLocation()->getName();?></td>
                            <td><?php print $miner->getName(); ?></td>
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
                                if ($miner->getStatus()) :
                                    switch ($miner->getLastStat()->getStatus()) :
                                        case LastStat::STATUS_OK:
                                            print '<label class="label label-success" title="Unit up and work properly" data-toggle="tooltip">OK</label>';
                                            break;

                                        case LastStat::STATUS_WARNING:
                                            $warnings = implode("<br>", $miner->getLastStat()->getWarnings());
                                            print '<label class="label label-warning" data-html="true" title="'.$warnings.'" data-toggle="tooltip">WARNING</label>';
                                            break;

                                        case LastStat::STATUS_FAILED:
                                            print '<label class="label label-danger" title="Unit down" data-toggle="tooltip">DOWN</label>';
                                            break;

                                        default:
                                            trigger_error(sprintf("Unknown status %u", $miner->getLastStat()->getStatus()), E_USER_WARNING);

                                    endswitch;
                                else :
                                    print '<label class="label label-default" title="Unit disabled" data-toggle="tooltip">DISABLED</label>';
                                endif;
                                ?>
                            </td>
                            <td>
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