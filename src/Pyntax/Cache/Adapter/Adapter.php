<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 7/05/15
 * Time: 11:47 AM
 */

namespace Pyntax\Cache\Adapter;


abstract class Adapter {

    protected $adapter = null;

    public function connect($serverAddress, $port ="", $username = "", $password) {

    }

    public function set($key, $value)
    {
        $this->adapter->set($key, $value);
    }

    public function get($key)
    {
        return $this->adapter->get($key);
    }
}