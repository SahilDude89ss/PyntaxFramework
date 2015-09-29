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

namespace Pyntax\Cache;
use Pyntax\Config\ConfigAwareInterface;

/**
 * Class CacheFactoryAbstract
 * @package Pyntax\Cache
 */
abstract class CacheFactoryAbstract implements CacheFactoryInterface, ConfigAwareInterface
{
    protected $_cache_manager = null;

    /**
     * @param array $config
     * @param $elementName
     * @param $beanName
     * @param string $customKeyName
     *
     * @return mixed
     */
    public function getConfigForElement(array $config, $elementName, $beanName, $customKeyName = 'beans')
    {
        if(!isset($config[$elementName])) {
            return false;
        }

        return $config[$elementName];
    }

    public function getFileName($fileName) {
        return $fileName."-".md5($fileName);
    }
}