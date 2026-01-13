<?php

namespace App;

/**
 * User rights constants and helper
 */
class UserRights
{
    /**
     * Right allowing a user to manage units
     */
    const MANAGE_UNITS = 1;

    /**
     * Right allowing a user to manage locations
     */
    const MANAGE_LOCATIONS = 2;

    /**
     * Right allowing a user to manage energy consumption records
     */
    const MANAGE_ENERGY_CONSUMPTION = 3;

    /**
     * Right allowing a user to view invoices
     */
    const SEE_INVOICES = 4;

    /**
     * Right allowing a user to manage other users
     */
    const MANAGE_USERS = 5;

    /**
     * Returns available user rights as an associative array (id => description)
     * @return array
     */
    public static function getUserRightsArray()
    {
        return array(
            self::MANAGE_UNITS => "User can manage units",
            self::MANAGE_LOCATIONS => "User can manage locations",
            self::MANAGE_ENERGY_CONSUMPTION => "User can manage energy consumption",
            self::SEE_INVOICES => "User can see invoices",
            self::MANAGE_USERS => "User can manage users"
        );
    }

}