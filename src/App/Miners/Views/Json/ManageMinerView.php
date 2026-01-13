<?php
/**

 * Time: 14:13
 */

namespace App\Miners\Views\Json;


use App\Location;
use App\Miner;
use App\Miners\Views\ManageMinerViewInterface;
use App\Model;
use App\Views\JsonView;
use App\Views\ViewInterface;

class ManageMinerView extends JsonView implements ViewInterface, \JsonSerializable, ManageMinerViewInterface
{

    /**
     * Название формы
     * @var string
     */
    protected $form_name = "Add unit form";
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
     * @inheritdoc
     */
    public function getAction(): string
    {
        return "/Miners/Add/Save";
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
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return array(
            "result" => $this->getResult(),
            "miner" => $this->getMiner()
        );
    }
}