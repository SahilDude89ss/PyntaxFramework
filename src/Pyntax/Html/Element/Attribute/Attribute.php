<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 17/07/15
 * Time: 3:42 PM
 */

namespace Pyntax\Html\Element\Attribute;


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