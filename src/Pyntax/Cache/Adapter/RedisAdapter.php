<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 7/05/15
 * Time: 11:46 AM
 */

namespace Pyntax\Cache\Adapter;
use \Redis;

class RedisAdapter extends Adapter {

    public function connect($serverAddress, $port = "", $username = "", $password = "")
    {
        $this->adapter = new Redis();
        $this->adapter->connect($serverAddress);
    }
}