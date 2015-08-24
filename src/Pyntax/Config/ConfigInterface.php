<?php

namespace Pyntax\Config;

/**
 * Interface ConfigInterface
 * @package Pyntax\Config
 */
interface ConfigInterface {

    public static function readConfig($key);

    public static function writeConfig($key, $value);

}