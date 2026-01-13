<?php

namespace App\EnergyConsumption\Views\Html;

use App\Db\PDOFactory;
use App\Views\BaseView;
use App\Views\ViewInterface;
use App\Bootstrap3\Helpers\Elements;
use App\Bootstrap3\Helpers\ResultError;
use App\Bootstrap3\Helpers\SelectLocation;
use App\Bootstrap3\Helpers\SelectModel;
use App\Datetime;
use App\Strings;
use App\Location;


class EnergyConsumptionView extends BaseView implements ViewInterface
{
    /**
     * Returns invoices
     * @var []
     */
    protected $invoices = array();
    protected $generatedInvoice;

    /**
     * @var Model[]
     */
    protected $models = array();
    /**
    * Locations for select
    * @var Location[]
     */
    protected $locations = array();

    /**
     * Get action
     * @return string
     */
    public function getAction(): string
    {
        return '/EnergyConsumption/NewInvoice';
    }

    /**
     * Returns invoices
     * @see invoices
     * @return array
     */
    public function getInvoices(): array
    {
        return $this->invoices;
    }

    /**
     * Sets invoices
     * @see invoices
     * @param [] $invoices
     * @return EnergyConsumptionView
     */
    public function setInvoices(array $invoices): EnergyConsumptionView
    {
        $this->invoices = $invoices;
        return $this;
    }


    /**
     * Get models
     * @see models
     * @return Model[]
     */
    public function getModels(): array
    {
        return $this->models;
    }

    /**
     * Sets models
     * @see models
     * @param Model[] $models
     * @return EnergyConsumptionView
     */
    public function setModels(array $models): EnergyConsumptionView
    {
        $this->models = $models;
        return $this;
    }

    /**
     * Get locations
     * @see locations
     * @return Location[]
     */
    public function getLocations(): array
    {
        return $this->locations;
    }

    /**
     * Sets locations
     * @see locations
     * @param Location[] $locations
     * @return EnergyConsumptionView
     */
    public function setLocations(array $locations): EnergyConsumptionView
    {
        $this->locations = $locations;
        return $this;
    }

    /**
     * Get locations
     * @see locations
     * @return Location[]
     */
    public function getLastDate(): int
    {
        return $this->lastDate ?? (time() - (24 * 60 * 60));
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
            
            <div class="row">

                <div class="col-sm-5 pull-left">
                    When generating an invoice, total energy consumption is calculated by summing the <code>uptime</code> entries from the corresponding table (<code>uptime_record</code>). The <code>uptime_record</code> table is populated from <code>last_stats</code>, with a date check — if a record is older it is written unchanged as <code>last_stats.uptime</code>. During summation (invoice generation), the previous <code>uptime_record</code> is found and its value is subtracted from the current entry (<code>uptime_record.uptime_value</code>). As a result, each invoice reflects only new, previously uncounted values.
                </div>

                <form class="form-horizontal col-sm-6 pull-right" action="<?php print $this->getAction(); ?>" method="post">
                    <?php

                    (new Elements\Text())
                        ->setTitle('From Date')
                        ->setName('from_date')
                        ->setValue(Datetime::create_force('@' . $this->getLastDate(), 'UTC')->format('m/d/Y H:i:s'))
                        ->setMaxLength(100)
                        ->setRequire(true)
                        ->out()
                    ;

                    (new Elements\Text())
                        ->setTitle('To Date')
                        ->setName('to_date')
                        ->setValue(Datetime::create_force('@' . time(), 'UTC')->format('m/d/Y H:i:s'))
                        ->setMaxLength(100)
                        ->setRequire(true)
                        ->out()
                    ;

                    // (new SelectModel($this->models))
                    //     ->setTitle('Model')
                    //     ->setName('model_id')
                    //     ->setRequire(true)
                    //     ->out();

                    (new SelectLocation($this->locations))
                        ->setTitle('Location')
                        ->setName('location_id')
                        ->setRequire(true)
                        ->out()
                    ;

                    (new Elements\Checkboxes())
                        ->setTitle('Do you want to see details on miners involved as well ?')
                        ->addCheckbox(
                            (new Elements\Checkbox())
                                ->setTitle('<small><code>BE AWARED</code> <span class="text-muted">It will take a bit more time to calculate.</span></small>')
                                ->setName('details')
                        )
                        ->out();

                    ?>
                    <br/>
                    <button type="submit" id="calcGen" class="btn btn-success pull-right">Calculate & Generate NEW INVOICE</button>
                </form>
            </div><br/><br/>

            <?php if (sizeof($this->invoices)) : ?>

                <h3>Energy Consumption Invoices</h3>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Paid Status</th>
                                <th>Cumulative Uptime</th>
                                <th>Energy Consumed (Uptime x 1.5 kW/h)</th>
                                <th>Miners Involved into Invoice</th>
                                <!-- <th>Miner Model (todo)</th> -->
                                <th>Location</th>
                                <th>Date From</th>
                                <th>Date To</th>
                                <th class="text-right">Invoice Page</th>
                            </tr>
                        </thead>

                        <?php foreach ($this->invoices as $invoice) : ?>
                            <tr>
                                <td><?php print $invoice->getStatus() ? '<span class="label label-success">PAID</span>' : '<span class="label label-danger">UNPAID</span>'; ?></td>
                                <td><?php print number_format($invoice->getUptimeCumulative() / 3600, 2) . ' Hours <small class="text-muted">/ ' . $invoice->getUptimeCumulative() . ' sec.</small>'; ?></td>
                                <td><?php print number_format($invoice->getUptimeCumulative() * 1.5 / 3600, 2) . ' kW/h'; ?></td>
                                <td><?php print ($invoice->getMinerAmount() ?? '--'); ?></td>
                                <!-- <td><?//php print ($invoice->getModelId() ?? '--'); ?></td> -->
                                <td><?php print Location::get($invoice->getLocationId(), false, PDOFactory::getReadPDOInstance())->getName(); ?></td>
                                <td><?php print gmdate('m/d/Y H:i:s', $invoice->getStartDate()); ?></td>
                                <td><?php print gmdate('m/d/Y H:i:s', $invoice->getInvoiceDate()); ?></td>
                                <td class="text-right">
                                    <a class="btn btn-xs btn-default" href="/EnergyConsumption/Invoice/<?php print $invoice->getId();?>"><small>FULL INFO</small></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

            <?php endif; ?>

            <script type="text/javascript">
                $(document).on('click', '#calcGen', function() {
                    new PNotify({ title: 'New Invoice', text: 'Please, be patient - it may take some time to calculate & generate an invoice. <br/><br/>You\'ll be redirected automatically, when it is ready.', type: 'success' });

                    $(this).addClass('disabled');
                    $(this).html('<i class="fa fa-spin fa-spinner"></i> Calculating ...');
                });
            </script>

        <?php
    }
}