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
use Pyntax\Html\Form\FormFactory;
use Pyntax\Html\Table\TableFactory;

/**
 * Class HtmlFactoryAbstract
 * @package Pyntax\Html
 */
abstract class HtmlFactoryAbstract implements HtmlFactoryInterface
{
    /**
     * @var null
     */
    protected $_form_factory = null;

    /**
     * @var null
     */
    protected $_html_factory = null;

    /**
     * @var null
     */
    protected $_table_factory = null;

    /**
     * @return bool
     */
    protected function setUpFormFactory() {
        if(!$this->_form_factory) {
            $this->_form_factory = new FormFactory();
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function setUpTableFactory() {
        if(!$this->_table_factory) {
            $this->_table_factory = new TableFactory();
        }

        return true;
    }
    /**
     * @return bool
     */
    protected function setUpHtmlFactory() {
        if(!$this->_html_factory) {
            $this->_html_factory = new HtmlFactory();
        }

        return true;
    }
}