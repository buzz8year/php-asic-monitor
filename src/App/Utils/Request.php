<?php

namespace App\Utils;

use App\Strings;

/**
 * Class Request
 * @package App\Utils
 */
class Request
{
    /**
     * @return bool
     */
    public static function isPost()
    {
        return isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === "POST";
    }

    /**
     * @return bool
     */
    public static function isGet()
    {
        return isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === "GET";
    }

    /**
     * @return bool
     */
    public static function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && mb_strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    /**
     * @param mixed $data
     * @param null $filters
     * @return mixed
     */
    public static function filter($data, $filters = null)
    {
        if (!isset($filters)) {
            $filters = Strings::TRIM | Strings::HTMLSPECIALCHARS;
        }

        if (is_array($data)) {
            foreach ($data as $_key => $value) {
                $data[$_key] = self::filter($value, $filters);
            }
        }

        if (!is_array($data) && $filters) {
            $data = (new Strings($data))->filter($filters);
        }

        return $data;
    }
}