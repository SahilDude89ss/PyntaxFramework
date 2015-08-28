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
        'input' => array(
            "button", "checkbox", "color", "date", "datetime", "datetime-local", "email", "file", "hidden", "image",
            "month", "number", "password", "radio", "range", "reset", "search", "submit", "tel", "text", "time", "url",
            "week",
        ),
        'button'
    );


//        //return "<{$elTag} class='{$_class}' id='{$_id}'>{$_element_data_value}</{$elTag}>";
//        $loader = new \Twig_Loader_Array(array(
//            'html_element_template' => "<{{elTag}} {% for attribute in attributes %}{{attribute.name}}='{{attribute.value}}'{% endfor %} {% if( elTagClosable == true) %}> {{elDataValue|raw}} </{{elTag}}>{% else %}value='{{elDataValue}}' />{% endif %}"
//        ));
//
//        $twig = new \Twig_Environment($loader);
//        $inputText =  $twig->render('html_element_template', array(
//            'elTag' => 'input',
//            'attributes' => array(
//                array(
//                    'name' => 'type',
//                    'value' => 'text'
//                )
//            ),
//            'elTagClosable' => false,
//            'elDataValue' => 'Sahil Sharma'
//        ));
//
//        echo $twig->render('html_element_template', array(
//            'elTag' => 'form',
//            'attributes' => array(
//                array(
//                    'name' => 'type',
//                    'value' => 'text'
//                ),
//                array(
//                    'name' => 'method',
//                    'value' => 'POST'
//                ),
//            ),
//            'elTagClosable' => true,
//            'elDataValue' => $inputText
//        ));
//        die;

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
                $_el_html .= " value='{$value} />";
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