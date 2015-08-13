<?php

namespace Pyntax\Config;

/**
 * Class Config
 * @package Pyntax\Config
 */
class Config extends ConfigAbstract {

    public function get($configName)
    {
        return isset($this->_config[$configName]) ? $this->_config[$configName] : false;
    }

    public function set($configName, $value)
    {
        $this->_config[$configName] = $value;
    }
}