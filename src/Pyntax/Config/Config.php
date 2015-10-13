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
 * Class Config
 * @package Pyntax\Config
 */
class Config extends ConfigAbstract
{
    /**
     * @param bool|false $defaultKey
     * @param bool|false $fileToBeLoaded
     */
    public function __construct($defaultKey = false, $fileToBeLoaded = false) {
        $this->_default_key = $defaultKey;
        $this->loadConfig($fileToBeLoaded);
    }

    /**
     * @param string $filesToBeLoaded
     * @return mixed
     */
    protected function loadConfig($filesToBeLoaded = "config.php")
    {
        $configFileDirectory = dirname(realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . self::$_config_folder_name)) . DIRECTORY_SEPARATOR . self::$_config_folder_name;

        if (empty($filesToBeLoaded)) {
            //Do not load anything if the file is empty
        } else if (is_array($filesToBeLoaded)) {
            foreach ($filesToBeLoaded as $_fileToBeLoaded) {
                include_once $configFileDirectory . DIRECTORY_SEPARATOR . $_fileToBeLoaded;
            }
        } else if (!empty($filesToBeLoaded)) {
            if (file_exists($configFileDirectory . DIRECTORY_SEPARATOR . $filesToBeLoaded)) {
                include_once $configFileDirectory . DIRECTORY_SEPARATOR . $filesToBeLoaded;
            }
        }
    }
}