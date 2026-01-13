<?php

namespace App;

class Validator
{
    /**
     * Validates an IPv4 address string
     * @param string $string
     * @return bool
     */
    public static function is_ipv4(string $string): bool
    {
        if (!preg_match("/^([0-9]+)\\.([0-9]+)\\.([0-9]+)\\.([0-9]+)$/i", $string, $match)) {
            return false;
        }

        if ($match[1] > 255 || $match[2] > 255 || $match[3] > 255 || $match[4] > 255) {
            return false;
        }

        return true;
    }

    /**
     * Validates a MAC address string
     * @param string $string
     * @return bool
     */
    public static function is_mac(string $string): bool
    {
        return (bool)preg_match("/^[0-9a-f]{2}\\:[0-9a-f]{2}\\:[0-9a-f]{2}\\:[0-9a-f]{2}\\:[0-9a-f]{2}\\:[0-9a-f]{2}$/i", $string);
    }
}