<?php
/**
 * Abstract base view class
 */
namespace App\Views;

use App\Result;

/**
 * Class BaseView
 * @package App\Views
 */
abstract class BaseView
{
    /**
     * Operation result passed to the view
     * @var Result
     */
    protected $result;

    /**
     * BaseView constructor.
     */
    public function __construct()
    {
        if (!isset($this->result)) {
            $this->result = new Result();
        }
    }

    /**
     * Returns the result
     * @see result
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Sets the result
     * @see result
     * @param Result $result
     * @return BaseView
     */
    public function setResult(Result $result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * Returns the output
     * @return string
     */
    public function fetch()
    {
        ob_start();
        $this->out();
        return ob_get_clean();
    }

    /**
     * Outputs the view
     * @return void
     */
    abstract public function out();
}