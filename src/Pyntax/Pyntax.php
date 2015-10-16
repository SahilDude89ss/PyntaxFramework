<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2015 Sahil Sharma (SahilDude89ss@gmail.com)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Pyntax;

use Pyntax\Config\Config;
use Pyntax\DAO\Adapter\AdapterInterface;
use Pyntax\DAO\Adapter\MySqlAdapter as DefaultMySqlAdapter;
use Pyntax\DAO\Bean\BeanFactory;
use Pyntax\DAO\Bean\BeanInterface;
use Pyntax\Html\Form\FormFactory;
use Pyntax\Html\Form\FormFactoryInterface;
use Pyntax\Html\HtmlFactory;

/**
 * Class Pyntax
 * @package Pyntax
 */
class Pyntax
{
    /**
     * @var null
     */
    static $BeanFactory = null;

    /**
     * @var null
     */
    static $FormFactory = null;

    /**
     * @var null
     */
    static $HtmlFactory = null;

    /**
     * @param $configPath
     * @throws \Exception
     */
    public static function start($configPath)
    {
        //Load all the files in the CachePath
        Config::loadConfigFiles($configPath);

        self::loadFactory();
        self::loadFormFactory();
        self::loadHtmlFactory();
    }

    /**
     * @param $beanName
     * @return bool | BeanInterface
     *
     * @throws \Exception
     */
    public static function getBean($beanName)
    {
        if (is_null(self::$BeanFactory)) {
            self::loadFactory();
        }

        if (self::$BeanFactory instanceof BeanFactory) {
            return self::$BeanFactory->getBean($beanName);

        }

        throw new \Exception("BeanFactory was empty. Please check if database config is set");
    }

    /**
     * @param BeanInterface $bean
     * @return bool
     */
    public static function generateForm(BeanInterface $bean)
    {
        if (is_null(self::$FormFactory)) {
            self::loadFormFactory();
        }

        if (self::$FormFactory instanceof FormFactoryInterface) {
            return self::$FormFactory->generateForm($bean);
        }

        return false;
    }

    public static function loadHtmlFactory()
    {
        if (is_null(self::$HtmlFactory)) {
            self::$HtmlFactory = new HtmlFactory();
        }

        return self::$HtmlFactory;
    }

    private static function loadFormFactory()
    {
        if (is_null(self::$FormFactory)) {
            self::$FormFactory = new FormFactory();
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private static function loadFactory()
    {
        $db_config = Config::read('database');
        $pdo = null;

        if (!empty($db_config['server']) && !empty($db_config['database'])) {
            $user = (isset($db_config['user'])) ? $db_config['user'] : 'root';
            $password = (isset($db_config['password'])) ? $db_config['password'] : "";

            $pdo = new \PDO('mysql:host=' . $db_config['server'] . ';dbname=' . $db_config['database'] . ';charset=utf8', $user, $password);

            if (!is_null($pdo)) {
                //load the MySqlAdapter from config.
                $_mysql_adapter = Config::read(array('core','MySQLAdapter'));

                if (isset($_mysql_adapter)) {
                    $mySqlAdapter = null;

                    try {
                        $mySqlAdapter = new $_mysql_adapter($pdo);
                    } catch (\Exception $e) {
                        throw $e;
                    }

                    if ($mySqlAdapter instanceof AdapterInterface) {
                        //Do nothing
                        self::$BeanFactory = new BeanFactory($mySqlAdapter);
                    } else {
                        self::$BeanFactory = new BeanFactory(new DefaultMySqlAdapter($pdo));
                    }
                }
                return true;
            }
        }


        return false;
    }
}