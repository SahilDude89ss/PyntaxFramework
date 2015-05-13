<?php
/**
 * Created by PhpStorm.
 * User: sahil
 * Date: 8/05/15
 * Time: 11:16 PM
 */

namespace Pyntax\Config;
use Pyntax\Common\ConfigInterface;

abstract class ConfigAbstract implements ConfigInterface {

    protected $_config = array();

    public function __construct() {
        if(file_exists('config/config.php')) {
            include_once "config/config.php";

            $this->_config = $pyntax_config;
        }
    }

}