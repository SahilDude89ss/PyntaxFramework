<?php
/**
 * Created by PhpStorm.
 * User: sahil
 * Date: 8/05/15
 * Time: 11:13 PM
 */

namespace Pyntax\Common;


interface ConfigInterface {

    public function get($configName);

    public function set($configName, $value);

}