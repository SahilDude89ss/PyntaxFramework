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
 */
abstract class ConfigAbstract implements ConfigInterface
{
    /**
     * This variable will hold Config data for all the variables and will only be accessible using the Config object.
     * This variables is marked as static because we only want one single source of truth.
     *
     * @var array
     */
    protected static $_config = array();

    /**
     * @var string
     */
    protected static $_config_folder_name = "config";

    /**
     * @var bool
     */
    protected $_default_key = false;

    /**
     * @param string $filesToBeLoaded
     * @return mixed
     */
    protected abstract function loadConfig($filesToBeLoaded = "config.php");

    /**
     * This function is sued to write multiple config variables to the static $_config variable
     * @param array $array
     * @return mixed
     */
    public function writeConfigArray(array $array = array())
    {
        foreach ($array as $key => $val) {
            $this->writeConfig($key, $val);
        }
    }

    /**
     * This function returns the value of the key in the config.
     * @param $key
     *
     * @return mixed
     */
    public function readConfig($key = false)
    {
        if (empty($key)) {
            if (isset($this->_default_key)) {
                return isset(self::$_config[$this->_default_key]) ? self::$_config[$this->_default_key] : false;
            }

            return isset(self::$_config) ? self::$_config : false;
        }

        if (isset($this->_default_key)) {
            return isset(self::$_config[$this->_default_key][$key]) ? self::$_config[$this->_default_key][$key] : false;
        }

        return isset(self::$_config[$key]) ? self::$_config[$key] : false;
    }

    /**
     * This function is used to write to the config variable.
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function writeToConfig($key, $value)
    {
        self::$_config[$key] = $value;
        return true;
    }

    public static function writeConfig($key, $value)
    {
        self::$_config[$key] = $value;
    }
}