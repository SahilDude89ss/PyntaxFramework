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
namespace Pyntax\Html;

use Pyntax\DAO\Bean\BeanInterface;
use Pyntax\Html\Form\FormFactoryInterface;
use Pyntax\Html\Table\TableFactoryInterface;

/**
 * Class HtmlFactory
 * @package Pyntax\Html
 */
class HtmlFactory extends HtmlFactoryAbstract
{

    /**
     * @param BeanInterface $bean
     * @param string $findCondition
     * @return bool|string
     */
    public function createTable(BeanInterface $bean, $findCondition = "") {
        if($this->_table_factory instanceof TableFactoryInterface) {
            return $this->_table_factory->generateTable($bean, $findCondition, true);
        }

        return false;
    }

    /**
     * @param BeanInterface $bean
     * @return bool|mixed
     */
    public function createForm(BeanInterface $bean) {
        if($this->_html_factory instanceof FormFactoryInterface) {
            return $this->_html_factory->generateForm($bean, true);
        }

        return false;
    }
}