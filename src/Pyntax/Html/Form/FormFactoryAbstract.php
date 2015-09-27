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

namespace Pyntax\Html\Form;

use Pyntax\Config\Config;
use Pyntax\DAO\Bean\BeanInterface;
use Pyntax\Html\Element\ElementFactory;

/**
 * Class FormFactory
 * @package Pyntax\Form
 */
abstract class FormFactoryAbstract extends ElementFactory implements FormFactoryInterface
{
    protected $_form_config = array();

    /**
     * @var array
     */
    static protected $_bean_meta_data = array();

    public function loadFormConfig() {
        $this->_form_config = !empty(Config::readConfig('form')) ? Config::readConfig('form') : array();
    }

    /**
     * @param BeanInterface $bean
     */
    public function setBeanMetaData(BeanInterface $bean)
    {
        if (!isset(self::$_bean_meta_data[$bean->getName()])) {
            self::$_bean_meta_data[$bean->getName()] = $bean;
        }
    }

    /**
     * @param null $_bean_name
     * @return array
     */
    public function getDisplayColumns($_bean_name = null)
    {
        if (!empty($_bean_name)) {
            $bean = $this->getBeanFromMetaData($_bean_name);
            if($bean instanceof BeanInterface) {
                return $bean->getDisplayColumns();
            }
        }

        return array();
    }

    /**
     * @param null $_bean_name
     * @return bool
     */
    protected function getBeanFromMetaData($_bean_name = null)
    {
        return isset(self::$_bean_meta_data[$_bean_name]) ? self::$_bean_meta_data[$_bean_name] : false;
    }

    /**
     * @param $columnName
     * @return string
     */
    public function convertColumnNameIntoLabel($columnName)
    {
        if (isset($this->_form_config['convertColumnNamesIntoLabel']) && $this->_form_config['convertColumnNamesIntoLabel'] == true) {
            $_new_labels = str_replace(array("_", "-"), " ", $columnName);
            return (ucwords($_new_labels));
        }

        return $columnName;
    }
}