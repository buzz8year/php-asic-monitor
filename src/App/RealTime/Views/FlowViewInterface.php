<?php

namespace App\RealTime\Views;

use App\RealTime\Flow;

interface FlowViewInterface
{
    /**
     * @see flow
     * @return Flow
     */
    public function getFlow(): Flow;

    /**
     * @see flow
     * @param Flow $flow
     * @return FlowViewInterface
     */
    public function setFlow(Flow $flow): FlowViewInterface;
}