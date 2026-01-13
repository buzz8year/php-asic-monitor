<?php

namespace App\Miners\Views\Html\ConfiguredDevices;

use App\ConfiguredDevice;
use App\Miners\Views\Html\EditView;
use App\Miners\Views\ManageMinerViewInterface;
use App\Views\ViewInterface;

/**
 * Class CreateFromView
 * @package App\Miners\Views\Html\ConfiguredDevices
 */
class CreateFromView extends EditView implements ViewInterface, ManageMinerViewInterface
{
    /**
     * @var ConfiguredDevice $config_device
     */
    protected $config_device;

    /**
     * Название формы
     * @var string
     */
    protected $form_name = "Add miner form";
    /**
     * HTML кнопки добавления
     * @var string
     */
    protected $btn_html = "Save new miner";

    /**
     * @return ConfiguredDevice
     */
    public function getConfigDevice(): ConfiguredDevice
    {
        return $this->config_device;
    }

    /**
     * @param ConfiguredDevice $config_device
     * @return $this
     */
    public function setConfigDevice(ConfiguredDevice $config_device): CreateFromView
    {
        $this->config_device = $config_device;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return "/Miners/ConfiguredDevices/CreateFrom/" . $this->config_device->getId() . "/";
    }
}