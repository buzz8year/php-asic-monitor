<?php

namespace App\RealTime\Views\Html;


use App\LineGraphs\LineGraph;
use App\LineGraphs\Views\Html\LineGraphView;
use App\RealTime\Flow;
use App\Views\HtmlView;
use App\Views\ViewInterface;

class IndexView extends HtmlView implements ViewInterface
{
    /**
     * @var Flow
     */
    protected $flow;
    /**
     * @var LineGraph[]
     */
    protected $graphs;

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
     * @return IndexView
     */
    public function setFlow(Flow $flow): IndexView
    {
        $this->flow = $flow;
        return $this;
    }

    /**
     * @see graphs
     * @return LineGraph[]
     */
    public function getGraphs(): array
    {
        return $this->graphs;
    }

    /**
     * @see graphs
     * @param LineGraph[] $graphs
     * @return IndexView
     */
    public function setGraphs(array $graphs): IndexView
    {
        $this->graphs = $graphs;
        return $this;
    }

        /**
     * @inheritdoc
     */
    public function out()
    {
        ?>

        <?php

        $flow_view = new FlowView();
        $flow_view->setFlow($this->flow);
        $flow_view->out();

        foreach ($this->getGraphs() as $graph) {
            (new LineGraphView())
                ->setGraph($graph)
                ->out();
        }

        ?>

        <?php
    }
}