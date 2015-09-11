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
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
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
    public static function loadConfig() {
        if (file_exists('config/config.php')) {
            include_once "config/config.php";
        } else {
            throw new \Exception('config/config.php not found!');
        }
    }

    public function __construct() {
        self::loadConfig();
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