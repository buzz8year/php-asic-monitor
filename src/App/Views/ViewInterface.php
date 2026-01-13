<?php 
namespace App\Views;

use App\Result;

interface ViewInterface
{
    /**
     * Returns the result
     * @see result
     * @return Result
     */
    public function getResult();

    /**
     * Sets the result
     * @see result
     * @param Result $result
     * @return BaseView
     */
    public function setResult(Result $result);

    /**
     * Outputs the view
     * @return void
     */
	public function out();

    /**
     * Fetches the view output
     * @return string
     */
	public function fetch();
}