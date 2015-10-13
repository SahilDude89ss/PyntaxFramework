<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2015 Sahil Sharma (SahilDude89ss@gmail.com)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

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

    /**
     * @throws \Exception
     */
    public static function loadConfig()
    {
        $configFileDirectory = dirname(realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "config")) . DIRECTORY_SEPARATOR . "config";
        $configFiles = scandir($configFileDirectory);
        if (is_array($configFiles)) {
            foreach ($configFiles as $_config_file) {
                if (preg_match('/.*\.php/', $_config_file)) {
                    if (is_file($configFileDirectory . DIRECTORY_SEPARATOR . $_config_file) &&
                        file_exists($configFileDirectory . DIRECTORY_SEPARATOR . $_config_file)
                    ) {
                        include_once $configFileDirectory . DIRECTORY_SEPARATOR . "{$_config_file}";
                    } else {
                        throw new \Exception("config/{$_config_file} not found!");
                    }
                }
            }
        }
    }

    public function __construct()
    {
        self::loadConfig();
    }

    /**
     * @param array $array
     */
    public static function writeConfigArray(array $array = array())
    {
        if (!empty($array)) {
            foreach ($array as $key => $val) {
                self::writeConfig($key, $val);
            }
        }
    }


    /**
     * @param $key
     * @param $value
     *
     * @return bool
     */
    public static function writeConfig($key, $value)
    {
        self::$_config[$key] = $value;
        return true;
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public static function readConfig($key)
    {
        return isset(self::$_config[$key]) ? self::$_config[$key] : false;
    }
}