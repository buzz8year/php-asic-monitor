<?php
/**
 * Generic result container
 */
namespace App;

/**
 * Class Result
 * @package App
 */
class Result implements \JsonSerializable
{
    /**
     * Operation result state
     * @var string success|error
     */
    protected $result = "success";

    /**
     * Success flag
     * @var bool
     */
    protected $success = true;

    /**
     * Error flag
     * @var bool
     */
    protected $error = false;

    /**
     * Errors list
     * @var array strings
     */
    protected $errors = array();

    /**
     * Important warnings
     * @var string[]
     */
    protected $warnings = array();

    /**
     * @inheritDoc
     */
    function jsonSerialize()
    {
        return array(
            "result" => $this->result,
            "success" => $this->success,
            "error" => $this->error,
            "errors" => $this->errors,
            "warnings" => $this->warnings,
            "has_warnings" => sizeof($this->warnings) && true
        );
    }

    /**
     * Returns true if the result is successful
     * @return bool
     */
    public function isSuccess()
    {
        return $this->result === "success";
    }

    /**
     * Returns errors
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Returns errors as a string
     * @param string $glue
     * @return null|string
     */
    public function getErrorsAsString($glue = ";")
    {
        return sizeof($this->getErrors()) ? implode($glue, $this->getErrors()) : null;
    }

    /**
     * Sets errors
     * @param array $errors
     * @return $this
     */
    public function setErrors(array $errors)
    {
        if (sizeof($errors)) {
            $this->result = "error";
            $this->success = false;
            $this->error = true;
        } else {
            $this->result = "success";
            $this->success = true;
            $this->error = false;
        }

        $this->errors = $errors;
        return $this;
    }

    /**
     * Adds an error
     * @param $error string|array
     * @param string|number $index
     * @return $this
     */
    public function addError($error, $index = null)
    {
        $this->result = "error";
        $this->success = false;
        $this->error = true;

        if (is_array($error)) {
            $this->errors = array_merge($this->errors, $error);
        } else {
            if (isset($index)) {
                $this->errors[$index] = $error;
            } else {
                $this->errors[] = $error;
            }
        }

        return $this;
    }

    /**
     * Returns whether there are errors
     * @return bool
     */
    public function hasErrors()
    {
        return sizeof($this->errors) > 0;
    }

    /**
     * Add an important warning
     * @param $string string Warning text
     * @return $this
     */
    public function addWarning($string)
    {
        $this->warnings[] = $string;
        return $this;
    }

    /**
     * Returns important warnings
     * @return string[]
     */
    public function getWarnings()
    {
        return $this->warnings;
    }

}