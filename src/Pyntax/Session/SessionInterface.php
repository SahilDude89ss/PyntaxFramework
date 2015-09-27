<?php
/**
 * Created by PhpStorm.
 * User: sahil
 * Date: 26/09/15
 * Time: 12:50 PM
 */

namespace Pyntax\Session;

/**
 * Interface SessionInterface
 * @package Pyntax\Session
 */
interface SessionInterface
{
    /**
     * @param $key
     * @return mixed
     */
    public function read($key);

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function write($key, $value);

    /**
     * @param $flag
     * @return mixed
     */
    public function setCacheFacade($flag);
}