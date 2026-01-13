<?php
/**
 * PHP errors handling class
 */

namespace App;

/**
 * Class ErrorsHandler
 * @package App
 */
class ErrorsHandler
{
    /**
     * Error message
     * @var string
     */
    public $message;

    /**
     * Fatal error flag
     * @var bool
     */
    public $fatal = false;

    /**
     * Mapping of error types
     * @var array
     */
    public static $errors = array(
        E_ERROR => "E_CORE_ERROR",
        E_WARNING => "E_WARNING",
        E_PARSE => "E_PARSE",
        E_NOTICE => "E_NOTICE",
        E_CORE_ERROR => "E_CORE_ERROR",
        E_CORE_WARNING => "E_CORE_WARNING",
        E_COMPILE_ERROR => "E_COMPILE_ERROR",
        E_COMPILE_WARNING => "E_COMPILE_WARNING",
        E_USER_ERROR => "E_USER_ERROR",
        E_USER_WARNING => "E_USER_WARNING",
        E_USER_NOTICE => "E_USER_NOTICE",
        E_STRICT => "E_STRICT",
        E_RECOVERABLE_ERROR => "E_RECOVERABLE_ERROR",
        E_DEPRECATED => "E_DEPRECATED",
        E_USER_DEPRECATED => "E_USER_DEPRECATED"
    );

    /**
     * Error handler
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @throws ApplicationException
     */
    public function process($errno, $errstr, $errfile, $errline)
    {
        if (!(error_reporting() & $errno)) {
            return;
        }

        switch ($errno) {
            case E_PARSE:
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            case E_RECOVERABLE_ERROR:
                $this->fatal = true;
                $this->message = sprintf("Fatal error %s: %s (%s, [%u]).", self::$errors[$errno], $errstr, $errfile, $errline);
                break;

            case E_WARNING:
            case E_CORE_WARNING:
            case E_USER_WARNING:
            case E_COMPILE_WARNING:
            case E_NOTICE:
            case E_USER_NOTICE:
            case E_STRICT:
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                $this->message = sprintf("Error %s: %s (%s, [%u]).", self::$errors[$errno], $errstr, $errfile, $errline);
                break;

            default:
                $this->message = sprintf("Unknown error %s: %s (%s, [%u]).", self::$errors[$errno], $errstr, $errfile, $errline);
        }

        if (ini_get("log_errors") != "0" && strtolower(ini_get("log_errors")) != "off") {
            error_log(
                sprintf(
                    "%s URI: %s",
                    $this->message,
                    (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "-")
                )
            );
        }

        if ($this->fatal || (defined("APP_DEVELOPMENT_VERSION") && APP_DEVELOPMENT_VERSION)) {
            throw new ApplicationException($this->message, 503);
        }

        if (ini_get("display_errors") != "0" && strtolower(ini_get("display_errors")) != "off") {
            print $this->message;
        }
    }
}