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
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
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
use Pyntax\Html\Element\Element;
use Pyntax\Html\Form\FormFactory;
use Pyntax\Html\Form\FormFactoryInterface;

/**
 * Class PyntaxDAO
 * @package Pyntax
 */
class PyntaxDAO
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
     * @var bool
     */
    static $PostSaveBeanId = false;

    public static function start() {
        Config::loadConfig();
        self::loadFactory();
        self::capturePostAndSaveBean();
    }

    /**
     * @return bool
     */
    protected static function capturePostAndSaveBean()
    {
        $formConfig = Config::readConfig('form');

        if(isset($formConfig['capturePostAndSaveBean']) && $formConfig['capturePostAndSaveBean'] ==  true) {
            $beanName = isset($_POST['PyntaxDAO']['BeanName']) ? $_POST['PyntaxDAO']['BeanName'] : false;

            if($beanName && isset($_POST['PyntaxDAO'][$beanName])) {
                $bean = self::getBean($beanName);
                foreach($_POST['PyntaxDAO'][$beanName] as $key => $val) {
                    $bean->$key = $val;
                }
                $bean->users_id = 9;
                self::$PostSaveBeanId = $bean->save();

                return self::$PostSaveBeanId;
            }
        }

        return false;
    }

    /**
     * @param $beanName
     * @return BeanInterface|boolean
     */
    public static function getBean($beanName)
    {
        if (is_null(self::$BeanFactory)) {
            self::loadFactory();
        }

        if (self::$BeanFactory instanceof BeanFactory) {
            return self::$BeanFactory->getBean($beanName);
        }
        return false;
    }

    /**
     * @param $elementName
     * @param $value
     * @param array $attributes
     *
     * @return Element
     */
    public static function generateHtmlElement($elementName, $value, array $attributes = array())
    {
        $el = new Element($elementName);
        $el->setValue($value);

        return $el;
    }

    /**
     * @param BeanInterface $bean
     * @return bool
     */
    public static function generateForm(BeanInterface $bean) {
        if(is_null(self::$FormFactory)) {
            self::loadFormFactory();
        }

        if(self::$FormFactory instanceof FormFactoryInterface) {
            return self::$FormFactory->generateForm($bean);
        }

        return false;
    }

    private static function loadFormFactory() {
        if(is_null(self::$FormFactory)) {
            self::$FormFactory = new FormFactory();
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private static function loadFactory()
    {
        $db_config = Config::readConfig('database');

        $pdo = null;

        if (!empty($db_config['server']) && !empty($db_config['database'])) {
            $user = (isset($db_config['user'])) ? $db_config['user'] : 'root';
            $password = (isset($db_config['password'])) ? $db_config['password'] : "";

            $pdo = new \PDO('mysql:host=' . $db_config['server'] . ';dbname=' . $db_config['database'] . ';charset=utf8', $user, $password);

            if (!is_null($pdo)) {
                //load the MySqlAdapter from config.
                $_core_config = Config::readConfig('core');

                if (isset($_core_config['MySQLAdapter'])) {
                    $mySqlAdapter = null;

                    try {
                        $mySqlAdapter = new $_core_config['MySQLAdapter']($pdo);
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