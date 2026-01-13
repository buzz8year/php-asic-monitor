<?php

namespace App;

class Strings
{
    const TRIM = 1;
    const HTMLSPECIALCHARS = 2;
    const FLOAT = 4;
    const UNSIGNED_INT = 8;
    const SIGNED_INT = 16;

    /**
     * Underlying string value
     * @var string
     */
    protected $string;

    /**
     * Strings constructor.
     * @param $string string
     */
    public function __construct($string)
    {
        $this->string = $string;
    }

    /**
     * Filter the string according to mask
     * @param int $mask
     * @return string
     */
    public function filter($mask)
    {
        $return = $this->string;

        if ($mask & self::TRIM) {

            $return = trim($return);
        }

        if ($mask & self::HTMLSPECIALCHARS) {

            $return = \htmlspecialchars($return, ENT_COMPAT, "utf-8");
        }

        if ($mask & self::FLOAT) {

            $return = preg_replace("/[^0-9\\.\\,]/", null, str_replace(",", ".", $return));
        }

        if ($mask & self::SIGNED_INT) {
            $return = (int)trim($return);
        }

        if ($mask & self::UNSIGNED_INT) {
            $return = abs((int)trim($return));
        }

        return $return;
    }

    /**
     * Applies htmlspecialchars to the string
     * @param string $string
     * @return string
     */
    public static function htmlspecialchars($string)
    {
        return (new self($string))->filter(self::HTMLSPECIALCHARS);
    }

    /**
     * Replace or add a parameter in the current URL query string
     * @param string|array $key
     * @param mixed $value
     * @return string
     */
    public static function http_build_query_replace($key, $value = null)
    {

        if (!is_array($key)) {

            $array = $_GET;

            $array[$key] = $value;

            $parts = array();

            foreach ($array as $param_key => $param_value) {

                if (!is_null($param_value)) {
                    $parts[] = Strings::htmlspecialchars($param_key) . "=" . Strings::htmlspecialchars($param_value);
                }
            }

            return implode("&", $parts);

        } else {

            $array = $_GET;

            foreach ($key as $index => $value) {

                $array[$index] = $value;
            }

            $parts = array();

            foreach ($array as $param_key => $param_value) {

                if (!is_null($param_value)) {
                    $parts[] = Strings::htmlspecialchars($param_key) . "=" . Strings::htmlspecialchars($param_value);
                }
            }

            return implode("&", $parts);
        }
    }

    /**
     * Format a number as money (with non-breaking space and currency symbol)
     * @param $number
     * @param string $sep
     * @param string $el
     * @return string
     */
    public static function money_format($number, $sep = "\xc2\xa0", $el = "\xc2\xa0р.")
    {
        $fixed_number = (float)preg_replace("/[^0-9\\.]/", null, str_replace(",", ".", $number));
        return number_format($fixed_number, 2, ",", $sep) . $el;
    }

    /**
     * Convert money formatted string (including RU locale) to float
     * @param string|null $string
     * @return float|int
     */
    public static function money_to_float($string)
    {
        if (is_null($string)) {
            return 0;
        }

        $string = preg_replace("/[^0-9\\.\\,]/", null, str_replace(",", ".", $string));

        if (!strlen($string)) {
            return 0;
        }

        return (float)$string;
    }

    /**
     * Convert from UTF-8 to CP1251
     * @param string $string
     * @return string
     */
    public static function convert_from_utf8_to_cp1251($string)
    {
        return iconv("utf8", "cp1251", $string);
    }

    /**
     * Replace Latin characters that visually resemble Cyrillic with Cyrillic equivalents
     * @param string $string
     * @return array|string
     */
    public static function normalize_from_lat2cyr($string)
    {
        /* $lat = array(
            "Q", "W", "E", "R", "T", "Y", "U", "I", "O", "P", "A", "S", "D", "F", "G", "H", "J", "K", "L", "Z", "X", "C", "V", "B", "N", "M",
            "q", "w", "e", "r", "t", "y", "u", "i", "o", "p", "a", "s", "d", "f", "g", "h", "j", "k", "l", "z", "x", "c", "v", "b", "n", "m"
        ); */

        $lat = array(
            "E", "T", "Y", "O", "P", "A", "H", "K", "X", "C", "B", "M", "e", "y", "u", "o", "p", "a", "k", "x", "c"
        );

        $rus = array(
            "Е", "Т", "У", "О", "Р", "А", "Н", "К", "Х", "С", "В", "М", "е", "у", "и", "о", "р", "а", "к", "х", "с"
        );

        return str_replace($lat, $rus, $string);
    }

    /**
     * Replace Cyrillic characters that visually resemble Latin with Latin equivalents
     * @param string $string
     * @return array|string
     */
    public static function normalize_from_cyr2lat($string)
    {
        $rus = array(
            "Е", "Т", "У", "О", "Р", "А", "Н", "К", "Х", "С", "В", "М", "е", "у", "и", "о", "р", "а", "к", "х", "с"
        );

        $lat = array(
            "E", "T", "Y", "O", "P", "A", "H", "K", "X", "C", "B", "M", "e", "y", "u", "o", "p", "a", "k", "x", "c"
        );

        return str_replace($rus, $lat, $string);
    }

    /**
     * Normalize characters similar to Roman numerals into normal Latin uppercase
     * @param string $string
     * @return array|string
     */
    public static function normalize_from_alphabet_to_roman($string)
    {
        $alphabet = array(
            "l", "1", "|", "!"
        );

        $roman = array(
            "I", "I", "I", "I"
        );

        return self::normalize_from_cyr2lat(str_replace($alphabet, $roman, $string));
    }

    /**
     * Returns the current URL prefix, e.g. "https://domain.tld"
     * @return string
     */
    public static function current_url_prefix()
    {
        $https =
            (
                (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
                || (getenv("HTTP_X_FORWARDED_PROTO") === "https")
                || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === "https")
            );

        $protocol = $https ? "https://" : "http://";
        $domainName = isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : "localhost";
        return $protocol . $domainName;
    }

    /**
     * Trim string
     * @param string|null $string
     * @return string
     */
    public static function trim($string): string
    {
        return (new self($string))->filter(self::TRIM);
    }
}
