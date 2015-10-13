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
 * Interface ConfigInterface
 * @package Pyntax\Config
 */
interface ConfigInterface {

    /**
     * This function is sued to write multiple config variables to the static $_config variable
     * @param array $array
     * @return mixed
     */
    public function writeConfigArray(array $array = array());

    /**
     * This function returns the value of the key in the config.
     *
     * @param bool|false $key
     * @return mixed
     */
    public function readConfig($key = false);

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function writeToConfig($key, $value);

    /**
     * This function is used to write to the config variable.
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public static function writeConfig($key, $value);

}