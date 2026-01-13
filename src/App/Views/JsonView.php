<?php
/**
 * Abstract class for JSON-type view rendering
 */
namespace App\Views;

/**
 * Class JsonViewInterface
 * @package App\Views
 */
abstract class JsonView extends BaseView implements \JsonSerializable, ViewInterface
{
    /**
     * Returns the output
     * @return string
     */
    public function fetch()
    {
        return $this->jsonSerialize();
    }

    /**
     * Outputs the view
     * @return void
     */
    public function out()
    {
        if (!headers_sent()) {
            header("Content-type: text/json; encode=\"UTF-8\"");
        }

        exit($this->fetch());
    }
}