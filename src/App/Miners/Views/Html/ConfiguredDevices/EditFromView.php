<?php

namespace App\Miners\Views\Html\ConfiguredDevices;

use App\Miners\Views\ManageMinerViewInterface;
use App\Views\ViewInterface;

class EditFromView extends CreateFromView implements ViewInterface, ManageMinerViewInterface
{
    /**
     * Название формы
     * @var string
     */
    protected $form_name = "Edit miner form";
    /**
     * HTML кнопки добавления
     * @var string
     */
    protected $btn_html = "Save changes";

    /**
     * @return string
     */
    public function getAction(): string
    {
        return "/Miners/ConfiguredDevices/EditFrom/" . $this->config_device->getId() . "/" . $this->miner->getId() . "/";
    }


}