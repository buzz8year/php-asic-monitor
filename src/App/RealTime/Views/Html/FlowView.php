<?php

namespace App\RealTime\Views\Html;


use App\RealTime\Flow;
use App\RealTime\Views\FlowViewInterface;
use App\Views\HtmlView;
use App\Views\ViewInterface;

class FlowView extends HtmlView implements ViewInterface, FlowViewInterface
{
    /**
     * @var Flow
     */
    protected $flow;

    /**
     * @see flow
     * @return Flow
     */
    public function getFlow(): Flow
    {
        return $this->flow;
    }

    /**
     * @see flow
     * @param Flow $flow
     * @return FlowViewInterface
     */
    public function setFlow(Flow $flow): FlowViewInterface
    {
        $this->flow = $flow;
        return $this;
    }

    /**
     * Setting warning "checked" state
     * @param string sessKey session key
     * @return string
     */
    public function warnChecked(string $sessKey) : string
    {
        if (isset($_SESSION['warn_params']) && $params = $_SESSION['warn_params']) {
            if (isset($params[$sessKey]) && (bool)$params[$sessKey]) {
                // return true;
                return 'checked';
            }
        }
        // return false;
        return '';
    }

    /**
     * @inheritdoc
     */
    public function out()
    {
        ?>

        <style type="text/css">
        .content {
            overflow-y: hidden;
        }
        .easyPieChart {
            min-height: 114px;
        }
        th {
            border: none!important;
        }
        td {
            border: none!important;
        }
        th:last-of-type, td:last-of-type {
            text-align: right;
        }
        input[type=checkbox] {
            position: relative;
            top: 2px;
            left: 19px;
        }
        .form-check code {
            border-radius: 0;
            padding-left: 20px;

        }
        </style>


        <div class="callout callout-danger">
            <h1>Attention!</h1>
            <div>
                Please, be awared! In order to decrease a load upon our mining worknet, we temporary stop the machines monitoring, this will help to keep mining process stable till we are able to extend the worknet, which can take up to two weeks. Also, please understand, holden monitoring does not mean that units are not mining/working - everything is just fine.
            </div>
        </div>


        <div class="row">
            <h2 class="col-xs-6 pull-left"><b>Real Time Flow</b></h2>
            <div class="col-xs-6 pull-right text-right">
                <br/>
                <div class="">
                    <ul class="list-inline">
                        <li><a class="btn btn-success" href="/RealTime/UnitsDetails">Show current units data</a></li>
                        <li><a class="btn btn-danger" id="appRefreshCharts" style="min-width: 120px"><i class="fa fa-refresh"></i> Refresh</a></li>
                        <li><a class="btn btn-default" id="appSwitchBeep" style="min-width: 120px"><i class="fa fa-volume-up"></i> <span>Volume on</span></a></li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div><br/><br/><br/>

        <div class="row">
            <div class="col-md-5">
                <!-- <div class="panel panel-animated panel-primary bg-primary animated fadeInUp" style="visibility: visible;"> -->
                <div class="callout callout-success callout-animated fadeIn" style="visibility: visible;">
                    <div class="panel-body">
                        <p>&nbsp;</p><!--/help-block-->
                        <p class="lead text-center">Current Units</p><!--/lead as title-->
                        <p>&nbsp;</p><!--/help-block-->

                        <ul class="list-percentages row">
                            <li class="col-xs-4">
                                <p class="text-ellipsis">Total Units</p>
                                <p class="text-lg"><strong><span id="appTotalUnit"><?php print $this->flow->getTotalUnit();?></span></strong></p>
                            </li>
                            <li class="col-xs-4">
                                <p class="text-ellipsis">Warning Units</p>
                                <p class="text-lg"><strong><span id="appWarningUnit"><?php print $this->flow->getWarningUnit();?></span></strong></p>
                            </li>
                            <li class="col-xs-4">
                                <p class="text-ellipsis">Failed Units</p>
                                <p class="text-lg"><strong><span id="appFailedUnit"><?php print $this->flow->getFailedUnit();?></span></strong></p>
                            </li>
                        </ul><!--/list-percentages-->
                        <ul class="list-percentages row">
                            <li class="col-xs-4">
                                <p class="text-ellipsis">Ideal Hashrate</p>
                                <p class="text-lg"><strong><span id="appIdealHashrate"><?php print sprintf("%0.4f", $this->flow->getIdealHashrate() / 1000)?></span> T/H</strong></p>
                            </li>
                            <li class="col-xs-4">
                                <p class="text-ellipsis">Current Hashrate</p>
                                <p class="text-lg"><strong><span id="appCurrentHashRate"><?php print sprintf("%0.4f", $this->flow->getCurrentHashrate() / 1000)?></span> T/H</strong></p>
                            </li>
                            <li class="col-xs-4">
                                <p class="text-ellipsis">Energy Consumption</p>
                                <p class="text-lg"><strong><span id="appEnergyConsumption"><?php print sprintf("%0.2f", ($this->flow->getTotalUnit() - $this->flow->getFailedUnit()) * 1.5) ?></span> K/Watt</strong></p>
                            </li>
                        </ul>
                        <!-- <p>&nbsp;</p> -->
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="text-center">
                            <div class="easy-pie-chart">
                                <div class="easyPieChart" id="appSuccessUnitPieChart" data-percent="<?php print sprintf("%u", $this->flow->getSuccessUnitsPercent());?>" data-barColor="#5BB75B">
                                    <span><?php print sprintf("%u", $this->flow->getSuccessUnitsPercent());?>%</span>
                                </div>
                                <div class="easyPieChart-label">Success Units</div>
                            </div>

                            <div class="easy-pie-chart">
                                <div class="easyPieChart" id="appWarningUnitsPieChart" data-percent="<?php print sprintf("%u", $this->flow->getWarningUnitsPercent());?>" data-barColor="#FAA732">
                                    <span><?php print sprintf("%u", $this->flow->getWarningUnitsPercent());?>%</span>
                                </div>
                                <div class="easyPieChart-label">Warning Units</div>
                            </div>

                            <div class="easy-pie-chart">
                                <div class="easyPieChart" id="appFailedUnitsPieChart" data-percent="<?php print sprintf("%u", $this->flow->getFailedUnitsPercent());?>" data-barColor="#DA4F49">
                                    <span><?php print sprintf("%u", $this->flow->getFailedUnitsPercent());?>%</span>
                                </div>
                                <div class="easyPieChart-label">Failed Units</div>
                            </div>

                            <div class="easy-pie-chart">
                                <div class="easyPieChart" id="appEfficiencyHashRatePieChart" data-percent="<?php print $this->flow->getEfficiencyHashrate();?>" data-barColor="#49AFCD">
                                    <span><?php print sprintf("%u", $this->flow->getEfficiencyHashrate());?>%</span>
                                </div>
                                <div class="easyPieChart-label">Efficiency Hashrate</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <!-- <div class="panel panel-animated panel-success bg-success animated fadeInUp" style="visibility: visible; margin-top: 12px"> -->
                        <div class="callout callout-success callout-animated animated fadeIn" style="visibility: visible">
                            <div class="panel-body">
                                <p class="lead text-center" style="margin-bottom: 25px">Current Temperature on Chips</p>
                                <!-- <p class="lead">&nbsp;</p> -->

                                <ul class="list-percentages row">
                                    <li class="col-xs-4">
                                        <p class="text-ellipsis">Average Temperature</p>
                                        <p class="text-lg"><strong><span id="appAverageTemp"><?php print ceil($this->flow->getTempChipsAvg());?></span> &#176;C</strong></p>
                                    </li>
                                    <li class="col-xs-4">
                                        <p class="text-ellipsis">Highest Temperature</p>
                                        <p class="text-lg"><strong><span id="appHighestTemp"><?php print $this->flow->getTempChipsMax();?></span> &#176;C</strong></p>
                                    </li>
                                    <li class="col-xs-4">
                                        <p class="text-ellipsis">Lowest Temperature</p>
                                        <p class="text-lg"><strong><span id="appLowestTemp"><?php print $this->flow->getTempChipMin();?></span> &#176;C</strong></p>
                                    </li>
                                </ul>
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div><br/><br/>

        <div class="row">
            <div class="col-xs-12">
                <form method="post" action="/RealTime">
                    <button type="submit"  class="btn btn-default pull-right" style="margin-left: 30px">Apply</button>
                    <div class="form-check pull-right">
                        <span>
                            <input type="hidden" name="warn_params[conn_lag]" value="0">
                            <input type="checkbox" name="warn_params[conn_lag]" value="1" class="form-check-input" id="connLag" <?php print $this->warnChecked('conn_lag'); ?> >
                            <label class="form-check-label" for="connLag"><code>Connection Lag</code></label>
                        </span>
                        <span>
                            <input type="hidden" name="warn_params[hw_error]" value="0">
                            <input type="checkbox" name="warn_params[hw_error]" value="1" class="form-check-input" id="hwError" <?php print $this->warnChecked('hw_error'); ?> >
                            <label class="form-check-label" for="hwError"><code>HW Error</code></label>
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($this->flow->getFailedUnits()): ?>
            <div id="appFailedUnitsCnt" class="callout callout-danger<?php print (!sizeof($this->flow->getFailedUnits()) ? " hidden" : "")?>">
                <!-- <h4><i class="fa fa-exclamation-triangle animated bounce infinite"></i> Failed Units</h4> -->
                <h4><i class="fa fa-exclamation-triangle animated swing infinite"></i> Failed Units</h4>
                <table id="appFailedUnitsList" class="table table-striped">
                    <thead>
                        <th>Location</th>
                        <th>IP Address</th>
                        <th>Stand / Shelf (if available)</th>
                        <th>Status / Warnings</th>
                    </thead>
                    <tbody>
                    <?php foreach ($this->flow->getFailedUnits() as $last_stat) : ?>
                        <tr>
                            <td><?php print $last_stat->getMiner()->getLocation()->getName()?></td>
                            <td><a href="/RealTime/FullData/<?php print $last_stat->getMinerId()?>" target="_blank"><?php print $last_stat->getMiner()->getIp()?></a></td>
                            <td class="text-uppercase"><?php print $last_stat->getMiner()->getShelf(); ?></td>
                            <td><label class="label label-danger">DOWN</label></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>


        <?php if ($this->flow->getWarningUnits()):?>
            <div id="appWarningUnitsCnt" class="callout callout-warning<?php print (!sizeof($this->flow->getWarningUnits()) ? " hidden" : "")?>">
                <h4><i class="fa fa-bell-o animated swing infinite"></i> Warning Units</h4>
                <table id="appWarningUnitsList" class="table table-striped">
                    <thead>
                        <th>Location</th>
                        <th>IP Address</th>
                        <th>Stand / Shelf (if available)</th>
                        <th>Status / Warnings</th>
                    </thead>
                    <tbody>
                    <?php foreach ($this->flow->getWarningUnits() as $last_stat):?>
                        <tr>
                            <td><?php print $last_stat->getMiner()->getLocation()->getName()?></td>
                            <td><a href="/RealTime/FullData/<?php print $last_stat->getMinerId()?>" target="_blank"><?php print $last_stat->getMiner()->getIp(); ?></a></td>
                            <td class="text-uppercase"><?php print $last_stat->getMiner()->getShelf(); ?></td>
                            <td><?php print implode(", ", $last_stat->getWarnings(true)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <br/><br/>

        <h3><b>Last 24 Hour</b></h3><br/><br/>

        <script>
            $(function () {
                var appRefreshCharts = $("#appRefreshCharts");
                var appTotalUnit = $("#appTotalUnit");
                var appWarningUnit = $("#appWarningUnit");
                var appFailedUnit = $("#appFailedUnit");

                var appIdealHashrate = $("#appIdealHashrate");
                var appCurrentHashRate = $("#appCurrentHashRate");
                var appEnergyConsumption = $("#appEnergyConsumption");

                var appSuccessUnitPieChart = $("#appSuccessUnitPieChart");
                var appWarningUnitsPieChart = $("#appWarningUnitsPieChart");
                var appFailedUnitsPieChart = $("#appFailedUnitsPieChart");
                var appEfficiencyHashRatePieChart = $("#appEfficiencyHashRatePieChart");

                var appAverageTemp = $("#appAverageTemp");
                var appHighestTemp = $("#appHighestTemp");
                var appLowestTemp = $("#appLowestTemp");

                var appFailedUnitsCnt = $("#appFailedUnitsCnt");
                var appFailedUnitsList = $("#appFailedUnitsList tbody");
                var appWarningUnitsCnt = $("#appWarningUnitsCnt");
                var appWarningUnitsList = $("#appWarningUnitsList tbody");

                var appSwitchBeep = $("#appSwitchBeep");
                var appSoundOn = true;

                function appUpdateCharts() {
                    $.ajax({
                        url: "/Api/RealTime/Flow",
                        dataType: "json",
                        cache: false,
                        beforeSend: function() {
                            if (!appRefreshCharts.data("original-inner-html")) {
                                appRefreshCharts.data("original-inner-html", appRefreshCharts.html());
                            }

                            appRefreshCharts.addClass("disabled");
                            appRefreshCharts.html('<i class="fa fa-spin fa-spinner"></i> Refreshing...');
                        },
                        complete: function() {
                            if (appRefreshCharts.data("original-inner-html")) {
                                appRefreshCharts.html(appRefreshCharts.data("original-inner-html"));
                            }

                            appRefreshCharts.removeClass("disabled");
                        },

                        success: function(data) {

                            appTotalUnit.html(data.content.flow.total_unit);
                            appWarningUnit.html(data.content.flow.warning_unit);
                            appFailedUnit.html(data.content.flow.failed_unit);
                            appIdealHashrate.html(data.content.flow.ideal_hashrate);
                            appCurrentHashRate.html(data.content.flow.current_hashrate);
                            appEnergyConsumption.html(data.content.flow.energy_consumption);
                            appAverageTemp.html(data.content.flow.average_temp);
                            appHighestTemp.html(data.content.flow.highest_temp);
                            appLowestTemp.html(data.content.flow.lowest_temp);

                            appSuccessUnitPieChart.data('easyPieChart').update(data.content.flow.success_units_percent);
                            appWarningUnitsPieChart.data('easyPieChart').update(data.content.flow.warning_units_percent);
                            appFailedUnitsPieChart.data('easyPieChart').update(data.content.flow.failed_units_percent);
                            appEfficiencyHashRatePieChart.data('easyPieChart').update(data.content.flow.efficiency_hashrate);

                            if (data.content.flow.failed_units.length) {
                                appFailedUnitsCnt.removeClass("hidden");
                                appFailedUnitsList.find("tr").remove();
                                $.each(data.content.flow.failed_units, function(index, last_stat) {
                                    appFailedUnitsList.append(
                                        '<tr>' +
                                        '  <td>' + last_stat.miner.location.name + '</td>' +
                                        '  <td><a href="/RealTime/FullData/' + last_stat.miner.id + '" target="_blank">' + last_stat.miner.ip + '</a></td>' +
                                        '  <td class="text-uppercase">' + last_stat.miner.shelf + '</td>' +
                                        '  <td><label class="label label-danger">DOWN</label></td>' +
                                        '</tr>'
                                    );
                                });
                            } else {
                                appFailedUnitsCnt.addClass("hidden");
                            }

                            if (data.content.flow.warning_units.length) {
                                appWarningUnitsCnt.removeClass("hidden");
                                appWarningUnitsList.find("tr").remove();
                                $.each(data.content.flow.warning_units, function(index, last_stat) {
                                    appWarningUnitsList.append(
                                        '<tr>' +
                                        '  <td>' + last_stat.miner.location.name + '</td>' +
                                        '  <td><a href="/RealTime/FullData/' + last_stat.miner.id + '" target="_blank">' + last_stat.miner.ip + '</a></td>' +
                                        '  <td class="text-uppercase">' + last_stat.miner.shelf + '</td>' +
                                        '  <td>' + $.makeArray(last_stat.warnings).join(', ') + '</td>' +
                                        '</tr>'
                                    );
                                });
                            } else {
                                appWarningUnitsList.addClass("hidden");
                            }

                            if (data.content.flow.failed_units.length) {
                                if (appSoundOn) $.playSound("/sounds/keys");
                                appFailedUnitsCnt.find("h4").addClass("animated flash").one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
                                    $(this).removeClass();
                                });
                            } else if (data.content.flow.warning_units.length) {
                                if (appSoundOn) $.playSound("/sounds/note");
                                appWarningUnitsCnt.find("h4").addClass("animated flash").one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
                                    $(this).removeClass();
                                });
                            }

                        }
                    });
                }

                appRefreshCharts.click(function(e) {
                    e.preventDefault();
                    appUpdateCharts();
                });

                setInterval(function() {
                    appUpdateCharts();
                }, 5000);


                var appDrowSoundBtn = function() {
                    var icon = appSwitchBeep.find("i");
                    var text = appSwitchBeep.find("span");

                    if (!appSoundOn) {
                        icon.removeClass("fa-volume-up")
                            .addClass("fa-volume-off");

                        appSwitchBeep
                            .addClass("text-muted");

                        text.html("Volume off");

                    } else {
                        icon.removeClass("fa-volume-off")
                            .addClass("fa-volume-up");

                        appSwitchBeep
                            .removeClass("text-muted");

                        text.html("Volume on");
                    }
                };

                if (localStorage) {
                    if (localStorage.getItem("appSoundOn") !== null) {
                        appSoundOn = JSON.parse(localStorage.getItem("appSoundOn"));
                        appDrowSoundBtn();
                    }
                }

                appSwitchBeep.click(function() {
                    appSoundOn = !appSoundOn;
                    if (localStorage) {
                        localStorage.setItem("appSoundOn", JSON.stringify(appSoundOn));
                    }
                    appDrowSoundBtn();
                });
            });
        </script>

        <?php
    }
}