<?php
/**

 * Time: 14:13
 */

namespace App\Miners\Views\Html;


use App\Miners\Views\ManageMinerViewInterface;
use App\Views\ViewInterface;

class AddMinerView extends EditView implements ViewInterface, ManageMinerViewInterface
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
    protected $btn_html = "Add";

    /**
     * Возвращает действие
     * @return string
     */
    public function getAction(): string
    {
        return sprintf("/Miners/Add/Save");
    }

}