<?php
/**
 * Exception class for 4xx HTTP errors
 */
namespace App;

/**
 * Class HttpError4xxException
 * @package App
 */
class HttpError4xxException extends \Exception
{
    public function __construct($message = "", $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        error_log(sprintf(
            "HttpError4xxException: 4XX Exception %u: %s. URI: %s (file %s, line %u)",
            $this->getCode(),
            $this->getMessage(),
            (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "-"),
            $this->getFile(),
            $this->getLine()
        ));
    }

}