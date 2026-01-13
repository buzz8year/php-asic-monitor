<?php

namespace App\EnergyConsumption\Views\Html;


use App\Db\PDOFactory;
use App\EnergyInvoice;
use App\Views\HtmlView;
use App\Views\ViewInterface;
use App\Location;

class NewInvoiceView extends HtmlView implements ViewInterface
{
    /**
     * @var EnergyInvoice
     */
    protected $invoice;
    /**
     * Last Invoice
     * @var EnergyInvoice
     */
    protected $lastInvoice;

    /**
     * Set invoice
     * @see invoice
     * @return EnergyInvoice
     */
    public function getInvoice(): EnergyInvoice
    {
        return $this->invoice;
    }

    /**
     * Get invoice
     * @see invoice
     * @param EnergyInvoice $invoice
     * @return NewInvoiceView
     */
    public function setInvoice(EnergyInvoice $invoice): NewInvoiceView
    {
        $this->invoice = $invoice;
        return $this;
    }


    /**
     * Set lastInvoice
     * @see lastInvoice
     * @return EnergyInvoice
     */
    public function getLastInvoice(): EnergyInvoice
    {
        return $this->lastInvoice;
    }

    /**
     * Get invoice
     * @see invoice
     * @param EnergyInvoice $lastInvoice
     * @return NewInvoiceView
     */
    public function setLastInvoice(EnergyInvoice $lastInvoice): NewInvoiceView
    {
        $this->lastInvoice = $lastInvoice;
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



            <h3>Newly Generated Energy Consumption Invoice</h3><br/><br/>

            <?php if ($this->invoice) : ?>

                <form class="form-horizontal" action="/EnergyConsumption/Invoice/Edit/<?= $this->invoice->getId() ?>/Save" method="post">
                    <input type="hidden" value="<?= $this->invoice->getStatus() ? 0 : 1 ?>" name="status" />
                    <button type="submit" class="btn btn-<?= $this->invoice->getStatus() ? 'danger' : 'success' ?>">Mark Invoice as <?= $this->invoice->getStatus() ? 'Unpaid' : 'Paid' ?></button>
                </form>
                <br/><br/>


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
                                <th>Paid Status</th>
                            </tr>
                        </thead>

                        <tr>
                            <td><?php print number_format($this->invoice->getUptimeCumulative() / 3600, 2) . ' Hours (' . $this->invoice->getUptimeCumulative() . ' sec.)'; ?></td>
                            <td><?php print number_format($this->invoice->getUptimeCumulative() * 1.5 / 3600, 2) . ' kW/h'; ?></td>
                            <td><?php print ($this->invoice->getMinerAmount() ?? '--'); ?></td>
                            <td><?php print Location::get($this->invoice->getLocationId(), false, PDOFactory::getReadPDOInstance())->getName(); ?></td>
                            <td><?php print gmdate('m/d/Y H:i:s', $this->invoice->getStartDate()); ?></td>
                            <td><?php print gmdate('m/d/Y H:i:s', $this->invoice->getInvoiceDate()); ?></td>
                            <td><?php print $this->invoice->getStatus() ? '<span class="label label-success">Paid</span>' : '<span class="label label-danger">Unpaid</span>'; ?></td>
                        </tr>
                    </table>
                </div>

                <h4>Energy Consumption by Miners Involved</h4>
                <div class="table-responsive">
                    <?php if ($this->invoice->getUptimeRecords()) : ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Miner ID</th>
                                    <!-- <th class="text-muted">Whole Uptime</th> -->
                                    <th>Uptime Difference for Current Invoice</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                            <?php foreach ($this->invoice->getUptimeRecords() as $record) : ?>
                                <tr>
                                    <td><?php print ($record->miner_id ?? '--'); ?></td>
                                    <!-- <td class="text-muted"><?//php print number_format($record->uptime_value / 3600, 2) . ' Hours (' . $record->uptime_value . ' sec.)'; ?></td> -->
                                    <td><?php print number_format($record->uptime_invoice / 86400, 2) . ' Days / ' . number_format($record->uptime_invoice / 3600, 2) . ' Hours <span>/ ' . $record->uptime_invoice . ' Sec.</span>'; ?></td>
                                    <td><?php print gmdate('H:m - M d, Y', $record->record_date); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else: ?>
                        <br/>
                        <div class="alert alert-warning pull-left">As you have chosen "not detailed" option on the invoice creation, there is no data on miners involved.</div>
                    <?php endif; ?>
                </div>

            <?php else: ?>

                <div class="alert alert-warning pull-left">The data used to generate a new invoice is not accumulated yet (since <a href="Invoice/<?php print $this->lastInvoice->getId(); ?>">last invoice</a>) or not enough - meaning there is no need for new invoice currently.</div>

            <?php endif; ?>

        <?php
    }
}