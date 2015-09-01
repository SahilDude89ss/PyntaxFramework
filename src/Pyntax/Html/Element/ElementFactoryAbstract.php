<?php

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
     * @param $tagName
     * @param array $attributes
     * @param string $value
     * @param bool|true $isClosable
     *
     * @return null|string
     */
    public function generateElementHtml($tagName, array $attributes = array(), $value ="", $isClosable = true)
    {
        if ($this->validateElement($tagName)) {
            $attributes = $this->generateAttributeHtml($attributes);

            $_el_html = "<{$tagName} {$attributes}";

            if($isClosable) {
                $_el_html .= ">{$value}</{$tagName}>";
            } else {
                $_el_html .= " value='{$value}' />";
            }

            return $_el_html;
        }

        return null;
    }

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

    /**
     * @param $elTag
     * @param $elData
     * @param $replaceNonStringCharacters
     * @return string
     */
    public function generateElement($elTag, $elData, $replaceNonStringCharacters = false)
    {
        $_element_data_value = $elData;
        $_class = "";
        $_id = "";

        if (is_array($elData) && isset($elData['data'])) {

            $_element_data_value = $elData['data'];

            if (isset($elData['class']) && !empty($elData['class'])) {
                $_class = $elData['class'];
            }

            if (isset($elData['id']) && !empty($elData['id'])) {
                $_id = $elData['id'];
            }
        }

        if (empty($_id)) {
            $_id = md5(time()) . "_" . $elTag;
        }

        if ($replaceNonStringCharacters) {
            $_element_data_value = strtoupper(str_replace("_", " ", $_element_data_value));
        }

        return "<{$elTag} class='{$_class}' id='{$_id}'>{$_element_data_value}</{$elTag}>";
    }
}