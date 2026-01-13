<?php
/**

 * Date: 11.04.2017
 * Time: 14:18
 */

namespace App\Layouts\Html;

use App\Layouts\BaseLayout;
use App\Layouts\LayoutInterface;
use App\Views\ViewInterface;

class HttpExceptionHtml extends BaseLayout implements LayoutInterface, ViewInterface
{
    /**
     * Сообщение об исключении
     * @var string
     */
    protected $message;
    /**
     * Код исключения
     * @var integer
     */
    protected $code;
    /**
     * Файл, в котором вызвано исключение
     * @var string
     */
    protected $file;
    /**
     * Строка, в которой вызвано исключение
     * @var int
     */
    protected $line;
    /**
     * Трассировка
     * @var string
     */
    protected $trace;

    /**
     * Возвращает message
     * @see message
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Устанавливает message
     * @see message
     * @param string $message
     * @return HttpExceptionHtml
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Возвращает code
     * @see code
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Устанавливает code
     * @see code
     * @param int $code
     * @return HttpExceptionHtml
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Возвращает file
     * @see file
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Устанавливает file
     * @see file
     * @param string $file
     * @return HttpExceptionHtml
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Возвращает line
     * @see line
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Устанавливает line
     * @see line
     * @param int $line
     * @return HttpExceptionHtml
     */
    public function setLine($line)
    {
        $this->line = $line;
        return $this;
    }

    /**
     * Возвращает trace
     * @see trace
     * @return string
     */
    public function getTrace()
    {
        return $this->trace;
    }

    /**
     * Устанавливает trace
     * @see trace
     * @param string $trace
     * @return HttpExceptionHtml
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
            switch ($this->getResponseCode()) {
                case 403:
                    header("HTTP/1.0 403 Forbidden");
                    break;

                case 404:
                    header("HTTP/1.0 404 Not found");
                    break;


                default:
                    trigger_error(sprintf("Unknown response HTTP code %u", $this->getResponseCode()), E_USER_WARNING);
                    header("HTTP/1.0 404 Not Found");
            }

            header("Content-Type: {$this->getContentMimeType()}; charset=utf-8");
        }

        ?><html>
        <head><title>Ошибка <?php print $this->getResponseCode();?></title></head>
        <body>
        <h1>Ошибка <?php print $this->getResponseCode();?></h1>
        Нажмите <a href="/">сюда</a>, что бы вернуться на главную страницу:
        <?php if (defined("APP_DEVELOPMENT_VERSION") && APP_DEVELOPMENT_VERSION):?>
            <h2>Информация для разработчика</h2>
            <div>
                <h3>Сообщение:</h3>
                <div><pre><?php print $this->getMessage();?></pre></div>

                <h3>Код исключения</h3>
                <div><pre><?php print $this->getCode();?></pre></div>

                <h3>Трассировка</h3>
                <div><pre><?php print $this->getTrace();?></pre></div>

            </div>
        <?php endif;?>
        </body>
        </html>
        <?php
    }
}