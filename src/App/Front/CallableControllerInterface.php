<?php
/**
 * Interface for controllers that can be invoked/called
 */

namespace App\Front;


use App\Layouts\LayoutInterface;
use App\Views\ViewInterface;

/**
 * Interface CallableControllerInterface
 * @package App\Front
 */
interface CallableControllerInterface
{
    /**
     * CallableControllerInterface constructor.
     * @param array $user_input_data
     * @param LayoutInterface $layout
     * @param ViewInterface $view
     */
    public function __construct(array $user_input_data, LayoutInterface $layout, ViewInterface $view);

    /**
    * Returns user_input_data
    * @see user_input_data
    * @return array
    */
    public function getUserInputData();

    /**
     * Sets user_input_data
     * @see user_input_data
     * @param array $user_input_data
     * @return CallableController
     */
    public function setUserInputData(array $user_input_data);

    /**
     * Returns layout
     * @see layout
     * @return LayoutInterface
     */
    public function getLayout();

    /**
     * Sets layout
     * @see layout
     * @param LayoutInterface $layout
     * @return CallableController
     */
    public function setLayout(LayoutInterface $layout);

    /**
     * Returns view
     * @see view
     * @return ViewInterface
     */
    public function getView();

    /**
     * Sets view
     * @see view
     * @param ViewInterface $view
     * @return CallableController
     */
    public function setView(ViewInterface $view);
}