<?php
/**

 * Date: 13.06.2018
 * Time: 16:12
 */

namespace App\Locations\Views\Html;


use App\Location;
use App\Views\HtmlView;
use App\Views\ViewInterface;

class IndexHtmlView extends HtmlView implements ViewInterface
{
    /**
     * Локации
     * @var Location[]
     */
    protected $locations = array();

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
     * @return IndexHtmlView
     */
    public function setLocations(array $locations): IndexHtmlView
    {
        $this->locations = $locations;
        return $this;
    }

    public function out()
    {
        ?>


        <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th class="text-muted">ID</th>
                            <th>Name</th>
                            <th class="text-muted">Description</th>
                            <th>Networks</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($this->getLocations() as $location):?>
                            <tr>
                                <td class="text-muted"><?php print $location->getId(); ?></td>
                                <td><?php print $location->getName(); ?></td>
                                <td class="text-muted"><?php print $location->getDescription(); ?></td>
                                <td><code><?php print $location->getNetworks(); ?></code></td>
                                <td class="text-right"><a class="btn btn-xs btn-primary" href="<?php print '/Miners/Location/' . $location->getId(); ?>"><small>VIEW MINERS</small></a></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <?php
    }
}