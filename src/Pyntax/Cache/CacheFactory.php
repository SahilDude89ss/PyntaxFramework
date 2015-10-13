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
use Pyntax\Config\Config;
use Zend\Cache\StorageFactory;

/**
 * Class CacheFactory
 * @package Pyntax\Cache
 */
class CacheFactory extends CacheFactoryAbstract
{
    protected $_cache_config = array();

    public function __construct() {
        $this->loadConfig();
        $this->loadCacheManager();
    }

    public function loadCacheManager() {
        $cacheAdapter = $this->getConfigForElement($this->_cache_config->readConfig(), 'adapter' , false, 'beans');
        $_cache_directory = $this->getConfigForElement($this->_cache_config->readConfig(), 'cacheDir' , false, 'beans');
        $cacheDirectory = !empty($_cache_directory) ? $_cache_directory  : "tmp/cache";

        if(is_writeable($_cache_directory)) {
            $this->_cache_manager = StorageFactory::adapterFactory($cacheAdapter, array('cacheDir' => $cacheDirectory));
        } else {
            throw new \Exception("Cache Manager: {$_cache_directory} is not writable");
        }
    }

    public function loadConfig() {
//        $this->_cache_config = Config::readConfig('cache');
        $this->_cache_config = new Config('cache','cache.config.php');
    }

    /**
     * @param $fileName
     * @return bool
     */
    public function read($fileName)
    {
        if($this->_cache_manager) {
            $_file_name = $this->getFileName($fileName);
            if($this->_cache_manager->hasItem($_file_name)) {
                return $this->_cache_manager->getItem($_file_name);
            }
        }

        return false;
    }

    /**
     * @param $fileName
     * @param $content
     * @param bool|false $expiry
     *
     * @return mixed
     */
    public function write($fileName, $content, $expiry = false)
    {
        if($this->_cache_manager) {
            $_file_name = $this->getFileName($fileName);
            return $this->_cache_manager->setItem($_file_name, serialize($content));
        }

        return false;
    }
}