<?php

namespace App\LineGraphs\Views\Html;

use App\LineGraphs\LineGraph;
use App\Views\HtmlView;
use App\Views\ViewInterface;

class LineGraphView extends HtmlView implements ViewInterface
{
    /**
     * Line graph
     * @var LineGraph
     */
    protected $graph;

    /**
     * Returns graph
     * @see graph
     * @return LineGraph
     */
    public function getGraph(): LineGraph
    {
        return $this->graph;
    }

    /**
     * Sets graph
     * @see graph
     * @param LineGraph $graph
     * @return LineGraphView
     */
    public function setGraph(LineGraph $graph): LineGraphView
    {
        $this->graph = $graph;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function out()
    {
        ?>

        <div class="row">
            <div class="col-xs-12 fadeInUp animated">
                <h3><?php print $this->getGraph()->getTitle();?></h3>
                <div id="<?php print $this->getGraph()->getElId();?>" style="height: <?php print $this->getGraph()->getHeight();?>"></div>
            </div>
        </div>

        <script>
            $(function() {
                var morris_area = $(document).find('#<?php print $this->getGraph()->getElId();?>');
                if (morris_area.length > 0) {
                    Morris.Line({
                        element: morris_area,
                        lineColors: <?php print json_encode($this->getGraph()->getLineColors()); ?>,
                        data: <?php print json_encode($this->getGraph()->getDataRows()); ?>,
                        xkey: "<?php print $this->getGraph()->getXkey(); ?>",
                        xLabels:  "<?php print $this->getGraph()->getXLabels(); ?>",
                        ykeys: <?php print json_encode($this->getGraph()->getYKeys());?>,
                        labels: <?php print json_encode($this->getGraph()->getLabels());?>,
                        pointSize: 2,
                        hideHover: 'auto',
                        ymax: <?php print $this->getGraph()->getYMax(); ?>,
                        ymin: <?php print $this->getGraph()->getYMin(); ?>
                    });
                }
            })
        </script>

        <br/><br/>

        <?php
    }
}