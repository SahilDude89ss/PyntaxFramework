<?php

namespace Pyntax\Config;

/**
 * Interface ConfigInterface
 * @package Pyntax\Config
 */
interface ConfigInterface {

    public function get($configName);

    public function set($configName, $value);

}