<?php

namespace App\EnergyConsumption\Views\Html;

use App\Db\PDOFactory;
use App\EnergyInvoice;
use App\Views\HtmlView;
use App\Views\ViewInterface;
use App\Location;

class InvoiceView extends HtmlView implements ViewInterface
{
    /**
     * Invoice
     * @var EnergyInvoice
     */
    protected $invoice;

    /**
     * Returns invoice
     * @see invoice
     * @return EnergyInvoice
     */
    public function getInvoice(): EnergyInvoice
    {
        return $this->invoice;
    }

    /**
     * Sets invoice
     * @see invoice
     * @param EnergyInvoice $invoice
     * @return InvoiceView
     */
    public function setInvoice(EnergyInvoice $invoice): InvoiceView
    {
        $this->invoice = $invoice;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function out()
    {
        ?>

            <style type="text/css">
                th:first-of-type {
                    padding-left: 0!important;
                }
            </style>

            <form class="form-horizontal" action="/EnergyConsumption/Invoice/Edit/<?= $this->invoice->getId() ?>/Save" method="post">
                <input type="hidden" value="<?= $this->invoice->getStatus() ? 0 : 1 ?>" name="status" />
                <button type="submit" class="btn btn-<?= $this->invoice->getStatus() ? 'danger' : 'success' ?>">Mark Invoice as <?= $this->invoice->getStatus() ? 'UNPAID' : 'PAID' ?></button>
            </form>
            <br/><br/>

            <h3>Energy Consumption Invoices</h3>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Cumulative Uptime</th>
                            <th>Energy Consumed (Uptime x 1.5 kW/h)</th>
                            <th>Miner Quantity</th>
                            <th>Location</th>
                            <th>Date From</th>
                            <th>Date To</th>
                            <th class="text-right">Paid Status</th>
                        </tr>
                    </thead>

                    <tr>
                        <td><?php print number_format($this->invoice->getUptimeCumulative() / 3600, 2) . ' Hours <small class="text-muted">/ ' . $this->invoice->getUptimeCumulative() . ' sec.</small>'; ?></td>
                        <td><?php print number_format($this->invoice->getUptimeCumulative() * 1.5 / 3600, 2) . ' kW/h'; ?></td>
                        <td><?php print ($this->invoice->getMinerAmount() ?? '--'); ?></td>
                        <td><?php print Location::get($this->invoice->getLocationId(), false, PDOFactory::getReadPDOInstance())->getName(); ?></td>
                        <td><?php print gmdate('m/d/Y H:i:s', $this->invoice->getStartDate()); ?></td>
                        <td><?php print gmdate('m/d/Y H:i:s', $this->invoice->getInvoiceDate()); ?></td>
                        <td class="text-right"><?php print $this->invoice->getStatus() ? '<span class="label label-success">PAID</span>' : '<span class="label label-danger">UNPAID</span>'; ?></td>
                    </tr>
                </table>
            </div><br/><br/>

            <h4>Energy Consumption by Miners Involved</h4>
            <div class="table-responsive">
                <?php if ($this->invoice->getUptimeRecords()) : ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Miner ID (Link)</th>
                                <th class="text-muted">Pool</th>
                                <th class="text-muted">Inner IP</th>
                                <!-- <th class="text-muted">Whole Uptime</th> -->
                                <th>Uptime Difference for Current Invoice</th>
                                <th class="text-muted">Record Date</th>
                                <th class="text-muted text-right">Machine Added To System</th>
                                <th class="text-right" data-placement="left" data-toggle="tooltip" data-title="Due to relocation, exchange, etc.">Currently Enabled / Disabled</th>
                            </tr>
                        </thead>

                        <?php foreach ($this->invoice->getUptimeRecords() as $record) : ?>
                            <tr>
                                <td><a class="btn btn-xs btn-default" href="/RealTime/FullData/<?php print $record->miner_id; ?>"><?php print $record->miner_id; ?></td>
                                <td class="text-muted"><?php print $record->stratum_url; ?></td>
                                <td class="text-muted"><?php print $record->miner_ip; ?></td>
                                <!-- <td class="text-muted"><?//php print number_format($record->uptime_value / 3600, 2) . ' Hours (' . $record->uptime_value . ' sec.)'; ?></td> -->
                                <td><?php print '<span>' . number_format($record->uptime_invoice / 86400, 2) . ' Days</span> <small>/ ' . number_format($record->uptime_invoice / 3600, 2) . ' Hours</small> <small class="text-muted">/ ' . $record->uptime_invoice . ' sec.</small>'; ?></td>
                                <td class="text-muted"><?php print gmdate('H:m - M d, Y', $record->record_date); ?></td>
                                <td class="text-muted text-right"><?php print gmdate('H:m - M d, Y', $record->miner_dtime); ?></td>
                                <td class="text-right"><?php print ($record->miner_status == 0 ? '<span class="label label-danger">DISABLED</span>' : '<span class="label label-success">ENABLED</span>'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <br/>
                    <div class="alert alert-warning pull-left">As you have chosen "not detailed" option on the invoice creation, there is no data on miners involved.</div>
                <?php endif; ?>
            </div>

        <?php
    }
}
