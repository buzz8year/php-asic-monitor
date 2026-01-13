<?php


switch ($_SERVER['HTTP_HOST'] ?? null) {
    case "stat.test":
        define("APP_MYSQL_HOST", "localhost");
        define("APP_MYSQL_USER", "www");
        define("APP_MYSQL_PASS", "www");
        define("APP_MYSQL_PORT", 3306);
        define("APP_MYSQL_DB", "stat");
        define("APP_DEVELOPMENT_VERSION", true);

        break;

    default:
        define("APP_MYSQL_HOST", "localhost");
        define("APP_MYSQL_USER", "dashboard");
        define("APP_MYSQL_PASS", "password398");
        define("APP_MYSQL_PORT", 3306);
        define("APP_MYSQL_DB", "monitoring");
        define("APP_DEVELOPMENT_VERSION", false);

}

define("APP_URL_PREFIX", \App\Strings::current_url_prefix());

ini_set("display_errors", "on");
ini_set("date.timezone", "Asia/Irkutsk");
error_reporting(E_ALL);