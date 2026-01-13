<?php
/**

 * Time: 14:13
 */

namespace App\Miners\Views\Html;


use App\Bootstrap3\Helpers\Elements;
use App\Bootstrap3\Helpers\ResultError;
use App\Bootstrap3\Helpers\SelectLocation;
use App\Bootstrap3\Helpers\SelectModel;
use App\Datetime;
use App\Location;
use App\Miner;
use App\Miners\Views\ManageMinerViewInterface;
use App\Model;
use App\Strings;
use App\Views\HtmlView;
use App\Views\ViewInterface;

class EditView extends HtmlView implements ViewInterface, ManageMinerViewInterface
{
    /**
     * Название формы
     * @var string
     */
    protected $form_name = "Edit unit form";
    /**
     * HTML кнопки добавления
     * @var string
     */
    protected $btn_html = "Save changes";

    /**
     * Майнер, который редактируется
     * @var Miner
     */
    protected $miner;
    /**
     * @var Model[]
     */
    protected $models = array();
    /**
     * Локации для Select
     * @var Location[]
     */
    protected $locations = array();

    /**
     * Возвращает действие
     * @return string
     */
    public function getAction(): string
    {
        return sprintf("/Miners/Edit/%u/Save", $this->miner->getId());
    }

    /**
     * Возвращает действие
     * @return string
     */
    public function getHostnameAction(): string
    {
        return sprintf("/Api/Miners/RequestHostname/%u", $this->miner->getId());
    }

    /**
     * Возвращает miner
     * @see miner
     * @return Miner
     */
    public function getMiner(): Miner
    {
        return $this->miner;
    }

    /**
     * Устанавливает miner
     * @see miner
     * @param Miner $miner
     * @return ManageMinerViewInterface
     */
    public function setMiner(Miner $miner): ManageMinerViewInterface
    {
        $this->miner = $miner;
        return $this;
    }

    /**
     * Возвращает models
     * @see models
     * @return Model[]
     */
    public function getModels(): array
    {
        return $this->models;
    }

    /**
     * Устанавливает models
     * @see models
     * @param Model[] $models
     * @return ManageMinerViewInterface
     */
    public function setModels(array $models): ManageMinerViewInterface
    {
        $this->models = $models;
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
     * @return ManageMinerViewInterface
     */
    public function setLocations(array $locations): ManageMinerViewInterface
    {
        $this->locations = $locations;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function out()
    {
        ?>

        <?php (new ResultError($this->getResult()))->out(); ?>

        <h3><?php print $this->form_name;?></h3>

        <form class="form-horizontal" action="<?php print $this->getAction();?>" method="post">
            <?php

            (new Elements\Text())
                ->setTitle("IP address")
                ->setName("ip")
                ->setValue(Strings::htmlspecialchars($this->miner->getIp()))
                ->setPlaceholder("IP Address")
                ->setMaxLength(64)
                ->setRequire(true)
                ->out()
                ;

            (new Elements\Text())
                ->setTitle("RPC Port")
                ->setName("port")
                ->setValue(Strings::htmlspecialchars($this->miner->getPort() ?? 4028))
                ->setPlaceholder("port")
                ->setMaxLength(5)
                ->setRequire(true)
                ->out()
            ;

            (new Elements\Text())
                ->setTitle("MAC address")
                ->setName("mac")
                ->setValue(Strings::htmlspecialchars($this->miner->getMac()))
                ->setPlaceholder("MAC address")
                ->setMaxLength(64)
                ->setRequire(true)
                ->out()
            ;

            (new SelectModel($this->models))
                ->setTitle("Choose model")
                ->setName("model_id")
                ->setValue($this->miner->getModelId())
                ->setRequire(true)
                ->out();

            (new SelectLocation($this->getLocations()))
                ->setTitle("Location")
                ->setName("allocation_id")
                ->setValue($this->miner->getAllocationId())
                ->setRequire(true)
                ->out()
            ;

            (new Elements\Text())
                ->setTitle("Mount date and time")
                ->setName("dtime")
                ->setValue($this->miner->getDtime() ? Datetime::create_force("@" . $this->miner->getDtime(), "UTC")->format("m/d/Y H:i:s") : date("m/d/Y H:i:s"))
                ->setPlaceholder("Mount date and time")
                ->setMaxLength(100)
                ->setRequire(true)
                ->out()
            ;

            (new Elements\Text())
                ->setTitle("Description")
                ->setName("description")
                ->setValue(Strings::htmlspecialchars($this->miner->getDescription()))
                ->setPlaceholder("Unit description")
                ->setMaxLength(255)
                ->out()
            ;

            (new Elements\Text())
                ->setTitle("Name")
                ->setName("name")
                ->setValue(Strings::htmlspecialchars($this->miner->getName()))
                ->setPlaceholder("Unit name")
                ->setMaxLength(150)
                ->setRequire(true)
                ->out()
            ;

            ?>

            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-9">
                    <div class="btn btn-sm btn-default" id="requestHostname">Request hostname</div> &nbsp;
                </div>
            </div>

            <br/>

            <?php

            (new Elements\Checkboxes())
                ->setTitle("Options")
                ->addCheckbox(
                    (new Elements\Checkbox())
                        ->setTitle("Active")
                        ->setName("status")
                        ->setChecked($this->miner->getId() ? $this->miner->getStatus() : true)
                )
                ->out();

            ?>

            <br/>

            <div class="row">
                <div class="col-sm-9 col-sm-offset-3">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>

        </form>

        <script>
            $(function () {

                var requestButton = $("#requestHostname");
                var hostnameInput = $("input[name=name]");

                $(document).on("click", "#requestHostname", function() {
                    $.ajax({
                        url: "<?php print $this->getHostnameAction(); ?>",
                        dataType: "json",
                        cache: false,
                        beforeSend: function() {
                            $("#requestError").remove();

                            requestButton
                                .html('<i class="fa fa-spin fa-spinner"></i> Requesting...')
                                .addClass("disabled");
                        },
                        complete: function() {
                            requestButton
                                .removeClass("disabled")
                                .html('Request hostname');
                        },
                        success: function(data) {
                            console.log(data);

                            if (!data.content.response.error && data.content.response.hostname) {
                                hostnameInput
                                    .val(data.content.response.hostname)
                                    .parent()
                                    .addClass("has-success has-feedback")
                                    .append('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>');
                            } 

                            else {
                                requestButton.after('<span id="requestError" class="btn btn-sm text-danger">' + data.content.response.error + '</span>');
                            }
                                
                        }
                    });
                });

            });
        </script>

        <?php
    }
}