<?php

namespace Pyntax\Config;

/**
 * Class ConfigAbstract
 * @package Pyntax\Config
 *
 */
abstract class ConfigAbstract implements ConfigInterface {

    protected $_config = array();

    public function __construct()
    {
        if(file_exists('config/config.php')) {
            $pyntax_config = include_once "config/config.php";

            $this->_config = $pyntax_config;
        }
    }

}