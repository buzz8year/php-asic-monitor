<?php

namespace App\Layouts\Html;

use App\Layouts\BaseLayout;
use App\Layouts\LayoutInterface;
use App\Views\ViewInterface;

class ApplicationExceptionHtml extends BaseLayout implements LayoutInterface, ViewInterface
{
    /**
     * @var string
     */
    protected $message;
    /**
     * @var integer
     */
    protected $code;
    /**
     * @var string
     */
    protected $file;
    /**
     * @var int
     */
    protected $line;
    /**
     * @var string
     */
    protected $trace;

    /**
     * Get message
     * @see message
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set message
     * @see message
     * @param string $message
     * @return ApplicationExceptionHtml
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Get code
     * @see code
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set code
     * @see code
     * @param int $code
     * @return ApplicationExceptionHtml
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Get file
     * @see file
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set file
     * @see file
     * @param string $file
     * @return ApplicationExceptionHtml
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Get line
     * @see line
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Set line
     * @see line
     * @param int $line
     * @return ApplicationExceptionHtml
     */
    public function setLine($line)
    {
        $this->line = $line;
        return $this;
    }

    /**
     * Get trace
     * @see trace
     * @return string
     */
    public function getTrace()
    {
        return $this->trace;
    }

    /**
     * Set trace
     * @see trace
     * @param string $trace
     * @return ApplicationExceptionHtml
     */
    public function setTrace($trace)
    {
        $this->trace = $trace;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function out()
    {
        if (!headers_sent()) {
            header(sprintf("HTTP/1.1 503 Internal error", $this->response_code), true, $this->response_code);
            header("Content-Type: {$this->getContentMimeType()}; charset=utf-8");
        }

        ?><html>
        <head><title>Error <?php print $this->getResponseCode();?>. Application Error.</title></head>
        <body>
        <h1>Application Error</h1>
        Click <a href="/">here</a>, to return to the main page:
        <?php if (defined("APP_DEVELOPMENT_VERSION") && APP_DEVELOPMENT_VERSION):?>
            <h2>Info for developers:</h2>
            <div>
                <h3>Message:</h3>
                <div><pre><?php print $this->getMessage();?></pre></div>

                <h3>Error Code:</h3>
                <div><pre><?php print $this->getCode();?></pre></div>

                <h3>Trace:</h3>
                <div><pre><?php print var_dump($this->getTrace());?></pre></div>
            </div>
        <?php endif;?>
        </body>
        </html>
        <?php
    }
}