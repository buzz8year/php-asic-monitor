<?php 
namespace App;

class Datetime
{
    /**
     * Returns current time
     * @param string|null $timezone
     * @return \DateTime
     */
    public static function now($timezone = null)
    {
        return self::create("now", $timezone);
    }

    /**
     * Create a DateTime object. Returns false on failure
     * @param string $datetime
     * @param string|null $timezone_in
     * @param string|null $timezone_out
     * @return \DateTime|false
     */
    public static function create($datetime, $timezone_in = "Europe/Oslo", $timezone_out =  "Europe/Oslo")
    {
        try {
            // Try to create from string
            if (!isset($timezone_in)) {
                // Timezone not specified
                $timezone_in = \ini_get("date.timezone") ? \ini_get("date.timezone") : "UTC";
            }

            $datetime = new \DateTime($datetime, new \DateTimeZone($timezone_in));
            if (isset($timezone_out)) {
                $datetime->setTimezone(new \DateTimeZone($timezone_out));
            }

            return $datetime;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Create a \DateTime object from a string. On error returns a 1970-01-01 \DateTime
     * @param string $datetime
     * @param string|null $timezone_in
     * @param string|null $timezone_out
     * @return \DateTime
     */
    public static function create_force($datetime, $timezone_in = "Europe/Oslo", $timezone_out =  "Europe/Oslo")
    {
        try {
            // Try to create from string
            if (!isset($timezone_in)) {
                // Timezone not specified
                $timezone_in = \ini_get("date.timezone") ? \ini_get("date.timezone") : "Europe/Oslo";
            }

            $datetime = new \DateTime($datetime, new \DateTimeZone($timezone_in));

            if (isset($timezone_out)) {
                $datetime->setTimezone(new \DateTimeZone($timezone_out));
            }

            return $datetime;

        } catch (\Exception $e) {
            // DateTime threw an exception - return epoch as fallback
            return new \DateTime("1970-01-01", new \DateTimeZone($timezone_in));
        }
    }

    /**
     * Returns elapsed time formatted in days/hours/minutes
     * @param int $seconds
     * @param string|null $nl
     * @return null|string
     */
    public static function elapse_datetime_format($seconds, $nl = null)
    {
        $seconds = (int)abs($seconds);
        $elapse_datetime = new \DateTime("@". $seconds);

        $return = null;

        if ($seconds >= 1440 * 60) {
            $return .= sprintf("%02d days %s", $elapse_datetime->format("d") - 1, $nl);
        }

        if ($seconds >= 60 * 60) {
            $return .= sprintf("%02d hours %s", $elapse_datetime->format("H"), $nl);
        }

        $return .= sprintf("%02d minutes %s", $elapse_datetime->format("i"), $nl);

        return $return;
    }

    /**
     * Returns date formatted according to given format
     * @param $string
     * @param string $format
     * @return null|string
     */
    public static function format($string, $format = "m/d/Y H:i:s")
    {
        if (!trim($string)) {
            return null;
        }

        $date = self::create($string);

        if (!$date) {
            return null;
        }

        return $date->format($format);
    }
}