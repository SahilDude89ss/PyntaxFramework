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
 * Class ElementFactory
 * @package Pyntax\Html\Element
 */
abstract class ElementFactoryAbstract implements ElementFactoryInterface
{
    /**
     * @var array
     */
    protected $_valid_html_elements = array(
        'a',
        'input',
        'button',
        'form'
    );

    /**
     * @param $attributes
     * @return mixed
     */
    protected function generateAttributeHtml($attributes)
    {
        if (is_string($attributes)) {
            return $attributes;
        }

        if (is_array($attributes)) {
            $attribute_string = "";

            foreach ($attributes as $attribute_name => $attribute_value) {
                if (is_string($attribute_name) && !empty($attribute_name)) {
                    if (is_string($attribute_value) && !empty($attribute_value)) {
                        $attribute_string .= "{$attribute_name}=\"{$attribute_value}\" ";
                    } else if (is_array($attribute_value) && !empty($attribute_value)) {
                        $_attribute_value = $this->generateAttributeForArrayValue($attribute_value);
                        if (!empty($_attribute_value)) {
                            $attribute_string .= "{$attribute_name}=\"{$_attribute_value}\" ";
                        }
                    }
                }
            }

            return $attribute_string;
        }

        return null;
    }

    /**
     * @param array $attributes
     * @return array
     */
    protected function cleanUpAttributeValues(array $attributes = array()) {
        $cleanedAttributes = array();

        foreach($attributes as $key => $val) {
            if(is_string($key)) {
                if(is_string($val)) {
                    $cleanedAttributes[] = array(
                        'name' => $key,
                        'value' => $val
                    );
                } else if(is_array($val)) {
                    $cleanedAttributes[] = array(
                        'name' => $key,
                        'value' => $this->generateAttributeForArrayValue($val)
                    );
                }
            }
        }

        return $cleanedAttributes;
    }

    /**
     * @param array $attributeValueArray
     * @return null|string
     */
    protected function generateAttributeForArrayValue(array $attributeValueArray = array())
    {
        if (!empty($attributeValueArray)) {
            $attribute_string = array();

            foreach($attributeValueArray as $_name => $_value) {
                if (is_string($_name) && !empty($_name)) {
                    if (is_string($_value) && !empty($_value)) {
                        $attribute_string[] = "{$_name}: {$_value}";
                    }
                }
            }

            return implode(";", $attribute_string);
        }

        return null;
    }

    /**
     * @param $tagName
     * @return bool
     */
    protected function validateElement($tagName)
    {
        return in_array($tagName, $this->_valid_html_elements);
    }


}