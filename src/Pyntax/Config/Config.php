<?php
/**
 * Created by PhpStorm.
 * User: sahil
 * Date: 8/05/15
 * Time: 11:15 PM
 */

namespace Pyntax\Config;

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