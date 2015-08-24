<?php

namespace Pyntax\Config;

/**
 * Class ConfigAbstract
 * @package Pyntax\Config
 *
 */
abstract class ConfigAbstract implements ConfigInterface
{
    /**
     * @var array
     */
    public static $_config = array();

    public function __construct()
    {
        if (file_exists('config/config.php')) {
            include_once "config/config.php";
        }
    }

    /**
     * @param $key
     * @param $val
     */
    public static function writeConfig($key, $val)
    {
        self::$_config[$key] = $val;
    }

    /**
     * @param $key
     * @return bool
     */
    public static function readConfig($key)
    {
        return isset(self::$_config[$key]) ? self::$_config[$key] : false;
    }

}