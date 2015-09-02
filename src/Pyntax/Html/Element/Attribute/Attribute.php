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

namespace Pyntax\Html\Element\Attribute;

/**
 * Class Attribute
 * @package Pyntax\Html\Element\Attribute
 */
class Attribute
{
    /**
     * @var string
     */
    protected $name = "";

    /**
     * @var string
     */
    protected $value = "";

    /**
     * @var bool
     */
    protected $isAttributeArray = false;

    /**
     * @param bool|false $isAttributeArray
     */
    public function __construct($isAttributeArray = false)
    {
        $this->isAttributeArray = $isAttributeArray;

        if ($this->isAttributeArray) {
            $this->value = array();
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $value
     * @param bool|false $arrayKey
     */
    public function setValue($value, $arrayKey = false)
    {
        if ($this->isAttributeArray == true) {
            if (is_array($this->value)) {
                $this->value[] = array(
                    $arrayKey => $value
                );
            } else {
                $this->value = array(
                    $value
                );
            }
        } else {
            $this->value = $value;
        }
    }

    /**
     * @param bool|false $returnString
     * @return string
     */
    public function generateHtml($returnString = false)
    {
        $value = "";
        if ($this->isAttributeArray == true) {
            $value = "{$this->getName()} = '";
            foreach ($this->getValue() as $attributeValueString => $value) {
                $value .= "{$attributeValueString} : {$value}; ";
            }
            $value .= "'";
        } else {
            $value = "{$this->getName()} = '{$this->getValue()}'";
        }


        if ($returnString) {
            return $value;
        } else {
            echo $value;
        }
    }
}