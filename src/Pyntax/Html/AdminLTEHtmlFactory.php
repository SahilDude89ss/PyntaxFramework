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

namespace Pyntax\Html;

use Pyntax\Html\Element\Element;
use Pyntax\Html\Element\ElementFactory;

/**
 * Class AdminLTEHtmlFactory
 * @package Pyntax\Html
 */
class AdminLTEHtmlFactory extends HtmlFactory
{
    public function createBoxHtml($headerHtml = "", $bodyHtml = "", $footerHtml = "", $attributes = array()) {
        if($this->_element_factory instanceof ElementFactory) {
            $_header_html = $this->_element_factory->generateElement('div', isset($attributes['header']) && is_array($attributes['header']) ? array_merge(array('class' => 'box-header'), $attributes) : array('class' => 'box-header'), $headerHtml);
            $_body_html = $this->_element_factory->generateElement('div', isset($attributes['body']) && is_array($attributes['body']) ? array_merge(array('class' => 'box-body'), $attributes) : array('class' => 'box-body'), $bodyHtml);
            $_footer_html = $this->_element_factory->generateElement('div', isset($attributes['footer']) && is_array($attributes['footer']) ? array_merge(array('class' => 'box-footer'), $attributes) : array('class' => 'box-footer'), $footerHtml);

            return $this->_element_factory->generateElement('div', array('box box-primary'), $_header_html.$_body_html.$_footer_html);
        }

    }
}