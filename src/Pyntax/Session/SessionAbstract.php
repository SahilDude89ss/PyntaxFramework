<?php
/**
 * Created by PhpStorm.
 * User: sahil
 * Date: 26/09/15
 * Time: 12:51 PM
 */

namespace Pyntax\Session;

use Pyntax\Config\Config;

/**
 * Class SessionAbstract
 * @package Pyntax\Session
 */
class SessionAbstract implements SessionInterface
{
    /**
     * @var array
     */
    protected $_config = array();

    /**
     * @var bool
     */
    protected $_use_PHP_session = true;

    /**
     * @var bool
     */
    protected $_use_cache_facade = false;

    /**
     * @var array
     */
    protected $_properties_to_be_loaded_from_config = array('use_PHP_session');

    public function loadConfig() {
        $this->_config = Config::readConfig('session');

        foreach($this->_properties_to_be_loaded_from_config as $key => $val) {
            if(isset($this->$key)) {
                $this->$key = $val;
            }
        }
    }

    /**
     * @param $key
     * @return bool
     */
    public function read($key) {
        return false;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return bool
     */
    public function write($key, $value)
    {
        if($this->_use_PHP_session) {
            $_SESSION[$key] = $value;
        } else if($this->_use_cache_facade) {
            //Write the VALUE to CACHE
        }

        return true;
    }

    /**
     * @param $flag
     * @return bool
     */
    public function setCacheFacade($flag)
    {
        $this->_use_cache_facade = true;

        return $this->_use_cache_facade;
    }
}