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
use Pyntax\Html\Element\Attribute\Attribute;

/**
 * Class Element
 * @package Pyntax\Html\Element
 */
class Element
{
    /**
     * @var array
     */
    protected $allowedTypes = array();

    /**
     * @var string
     */
    protected $tagName = "";

    /**
     * @var bool
     */
    protected $isTagClosable = true;

    /**
     * List of attributes for an Element
     *
     * @var array
     */
    protected $attributes = array();

    /**
     * @var string
     */
    protected $value = "";

    /**
     * @param $tableElementName
     * @param bool|false $isTagClosable
     */
    public function __construct($tableElementName, $isTagClosable = false)
    {
        if (!empty($this->allowedTypes) && !$this->validateElementType($tableElementName)) {
            return false;
        }

        $this->setTagName($tableElementName);
        $this->setIsTagClosable($isTagClosable);
    }

    /**
     * @param $tableElementName
     * @return bool
     */
    protected function validateElementType($tableElementName)
    {
        return (is_array($this->allowedTypes) && in_array($tableElementName, $this->allowedTypes)) ? true : false;
    }

    /**
     * addAttribute adds an attribute to the element.
     *
     * @param $name
     * @param $value
     * @param bool|false $isAttributeArray
     */
    public function addAttribute($name, $value, $isAttributeArray = false)
    {
        $attribute = new Attribute($isAttributeArray);
        $attribute->setName($name);
        $attribute->setValue($value);

        $this->attributes[] = $attribute;
    }

    /**
     * @param array $attributes
     */
    public function addAttributes(array $attributes = array()) {
        foreach($attributes as $attribute => $value) {
            $this->addAttribute($attributes, $value);
        }
    }

    /**
     * @param bool|false $returnString
     * @return string
     */
    public function generateHtml($returnString = false)
    {
        $elementString = "<" . $this->getTagName() . " ";

        foreach ($this->attributes as $attribute) {
            $elementString .= $attribute->generateHtml(true);
        }

        if ($this->isIsTagClosable()) {
            $elementString .= ">" . $this->getValue() . "</" . $this->getTagName() . ">";
        } else {
            $elementString .= " value='{$this->getValue()}' />";
        }

        if ($returnString) {
            return $elementString;
        } else {
            echo $elementString;
        }
    }

    /**
     * @return string
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * @param string $tagName
     */
    public function setTagName($tagName)
    {
        $this->tagName = $tagName;
    }

    /**
     * @return boolean
     */
    public function isIsTagClosable()
    {
        return $this->isTagClosable;
    }

    /**
     * @param boolean $isTagClosable
     */
    public function setIsTagClosable($isTagClosable)
    {
        $this->isTagClosable = $isTagClosable;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}