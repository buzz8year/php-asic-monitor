<?php
/**
 * Dispatcher
 */

namespace App\Front;

use App;

/**
 * Class Dispatcher
 * @package App\Front
 */
class Dispatcher
{
    /**
     * Invocation path
     * @var string
     */
    protected $path;
    /**
     * Controller instance
     * @var CallableControllerInterface
     */
    protected $controller_entity;

    /**
     * Dispatcher constructor.
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;

        $this->path = preg_replace("#/{2,}#", "/", $this->path);

        if (!$this->path) {
            $this->path = "/";
        }
    }

    /**
     * Dispatches the controller call
     * @return CallableControllerInterface|false
     */
    public function dispatch()
    {
        /* if (preg_match("#^/?Api/Login/?$#", $this->path)) {
            // API Login
            return $this->controller_entity = (new App\Login\FrontCallableControllers\Login($_REQUEST, new App\Layouts\DefaultJson(), new App\Login\Views\LoginJson()))->index();
        } */

        return false;
    }

    /**
     * Returns controller_entity
     * @see controller_entity
     * @return CallableControllerInterface
     */
    public function getControllerEntity()
    {
        return $this->controller_entity;
    }

    /**
     * Sets controller_entity
     * @see controller_entity
     * @param CallableControllerInterface $controller_entity
     * @return Dispatcher
     */
    public function setControllerEntity($controller_entity)
    {
        $this->controller_entity = $controller_entity;
        return $this;
    }

}