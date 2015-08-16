<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 17/07/15
 * Time: 3:42 PM
 */

namespace Pyntax\Html\Element;


use Pyntax\Html\Element\Attribute\Attribute;

class Element
{
    /**
     * @var array
     */
    protected $allowedTypes = array(
        'input', 'a'
    );

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
        if (!$this->validateElementType($tableElementName)) {
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