<?php
/**

 * Date: 11.04.2017
 * Time: 19:06
 */

namespace App\Front;


use App\Layouts\LayoutInterface;
use App\Views\ViewInterface;

abstract class CallableController
{
        /**
        * Incoming user input data
        * @var array
        */
    protected $user_input_data;
    /**
     * Output layout
     * @var LayoutInterface
     */
    protected $layout;
    /**
     * View used by the controller
     * @var ViewInterface
     */
    protected $view;

    /**
     * CallableController constructor.
     * @param array $user_input_data
     * @param LayoutInterface $layout
     * @param ViewInterface $view
     */
    public function __construct(array $user_input_data, LayoutInterface $layout, ViewInterface $view)
    {
        $this->user_input_data = $user_input_data;
        $this->layout = $layout;
        $this->view = $view;
    }

    /**
    * Returns user_input_data
    * @see user_input_data
    * @param mixed|null $key
    * @return mixed
     */
    public function getUserInputData($key = null)
    {
        if (isset($key)) {
            return isset($this->user_input_data[$key]) ? $this->user_input_data[$key] : null;
        }
        return $this->user_input_data;
    }

    /**
     * Sets user_input_data
     * @see user_input_data
     * @param array $user_input_data
     * @return CallableController
     */
    public function setUserInputData(array $user_input_data)
    {
        $this->user_input_data = $user_input_data;
        return $this;
    }

    /**
     * Returns the layout
     * @see layout
     * @return LayoutInterface
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Sets the layout
     * @see layout
     * @param LayoutInterface $layout
     * @return CallableController
     */
    public function setLayout(LayoutInterface $layout)
    {
        $this->layout = $layout;
        return $this;
    }

    /**
     * Returns the view
     * @see view
     * @return ViewInterface
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Sets the view
     * @see view
     * @param ViewInterface $view
     * @return CallableController
     */
    public function setView(ViewInterface $view)
    {
        $this->view = $view;
        return $this;
    }


}