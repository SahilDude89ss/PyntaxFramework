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

namespace Pyntax\Html\Element;

/**
 * Attribute	Description
 * accept-charset	Specifies the charset used in the submitted form (default: the page charset).
 * action	Specifies an address (url) where to submit the form (default: the submitting page).
 * autocomplete	Specifies if the browser should autocomplete the form (default: on).
 * enctype	Specifies the encoding of the submitted data (default: is url-encoded).
 * method	Specifies the HTTP method used when submitting the form (default: GET).
 * name	Specifies a name used to identify the form (for DOM usage: document.forms.name).
 * novalidate	Specifies that the browser should not validate the form.
 * target	Specifies the target of the address in the action attribute (default: _self).
 *
 * Class Form
 * @package Pyntax\Html\Element
 */
class Form extends Element
{
    const METHOD_TYPE_GET = 'GET';

    const METHOD_TYPE_POST = 'POST';

    /**
     * @var array
     */
    protected $_default_attributes = array(
        'accept-charset' => '',
        'action' => '',
        'autocomplete' => '',
        'enctype' => '',
        'method' => self::METHOD_TYPE_GET,
        'name' => '',
        'novalidate' => '',
        'target' => ''
    );

    public function __construct($name, array $attributes = array()) {
        parent::__construct('form', true);
        $this->addAttributes($attributes);
    }
}