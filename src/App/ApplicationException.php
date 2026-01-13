<?php
/**
 * Application exception class
 */

namespace App;

/**
 * Class ApplicationException
 * @package App
 */
class ApplicationException extends \Exception
{
    public function __construct($message = "", $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        error_log(sprintf(
            "ApplicationException: Application exception %u: %s. URI: %s (file %s, line %u)",
            $this->getCode(),
            $this->getMessage(),
            (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "-"),
            $this->getFile(),
            $this->getLine()
        ));

    }

}