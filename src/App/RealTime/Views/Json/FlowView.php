<?php

namespace App\RealTime\Views\Json;

use App\RealTime\Flow;
use App\RealTime\Views\FlowViewInterface;
use App\Views\JsonView;
use App\Views\ViewInterface;

class FlowView extends JsonView implements ViewInterface, FlowViewInterface, \JsonSerializable
{
    /**
     * @var Flow
     */
    protected $flow;

    /**
     * @inheritDoc
     */
    public function getFlow(): Flow
    {
        return $this->flow;
    }

    /**
     * @inheritDoc
     */
    public function setFlow(Flow $flow): FlowViewInterface
    {
        $this->flow = $flow;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return array(
            "result" => $this->getResult(),
            "flow" => array(
                "total_unit" => $this->flow->getTotalUnit(),
                "warning_unit" => $this->flow->getWarningUnit(),
                "failed_unit" => $this->flow->getFailedUnit(),
                "ideal_hashrate" => sprintf("%0.4f", $this->flow->getIdealHashrate() / 1000),
                "current_hashrate" => sprintf("%0.4f", $this->flow->getCurrentHashrate() / 1000),
                "energy_consumption" => sprintf("%0.2f", ($this->flow->getTotalUnit() - $this->flow->getFailedUnit()) * 1.5),
                "success_units_percent" => sprintf("%u", $this->flow->getSuccessUnitsPercent()),
                "warning_units_percent" => sprintf("%u", $this->flow->getWarningUnitsPercent()),
                "failed_units_percent" => sprintf("%u", $this->flow->getFailedUnitsPercent()),
                "efficiency_hashrate" => sprintf("%u", $this->flow->getEfficiencyHashrate()),
                "average_temp" => ceil($this->flow->getTempChipsAvg()),
                "highest_temp" => $this->flow->getTempChipsMax(),
                "lowest_temp" => $this->flow->getTempChipMin(),
                "warning_units" => $this->flow->getWarningUnits(),
                "failed_units" => $this->flow->getFailedUnits()
            )
        );
    }

}