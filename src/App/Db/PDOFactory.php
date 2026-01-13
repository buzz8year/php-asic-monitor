<?php
namespace App\Db;

class PDOFactory
{
    /**
     * \PDO instance for writing
     * @var \PDO
     */
    protected static $write_instance;

    /**
     * \PDO instance for reading
     * @var \PDO
     */
    protected static $read_instance;

    /**
     * Returns a PDO instance for writing
     * @return \PDO
     */
    public static function getWritePDOInstance()
    {
        if (!isset(self::$write_instance)) {
            self::$write_instance = new \PDO(
                sprintf("mysql:host=%s;port=%u;dbname=%s;charset=UTF8",APP_MYSQL_HOST,APP_MYSQL_PORT, APP_MYSQL_DB),
                APP_MYSQL_USER,
                APP_MYSQL_PASS,
                array(
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8",
                    \PDO::ATTR_EMULATE_PREPARES => false
                )
            );

            self::$write_instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        return self::$write_instance;
    }

    /**
     * Returns a PDO instance available for reading
     * @return \PDO
     */
    public static function getReadPDOInstance()
    {
        return self::getWritePDOInstance();
    }
}