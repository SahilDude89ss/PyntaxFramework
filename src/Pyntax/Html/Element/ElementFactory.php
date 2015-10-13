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

namespace Pyntax\Html\Element;

use Pyntax\Config\Config;
use Pyntax\DAO\Bean\BeanInterface;
use Pyntax\DAO\Bean\Column\ColumnInterface;

/**
 * Class ElementFactory
 * @package Pyntax\Html\Element
 */
class ElementFactory extends ElementFactoryAbstract
{
    protected $_module_config_key = false;

    public function __construct()
    {
        if (!$this->_twig_environment) {
            $this->setupTwig();
        }
    }

    /**
     * @param $key
     */
    public function setModuleConfigKey($key) {
        $this->_module_config_key = $key;
    }

    protected function setupTwig()
    {
        $_default_template = array(
            'html_element_template' => "<{{elTag}} {% for attribute in attributes %}{{attribute.name}}='{{attribute.value}}'{% endfor %} {% if( elTagClosable == true) %}> {{elDataValue|raw}} </{{elTag}}>{% else %}value='{{elDataValue}}' />{% endif %}",
        );

        $_config = new Config('template');

        foreach(array_keys($_default_template) as $k => $v) {
            $_overriding_value = $_config->readConfig($v);
            if(!empty($_overriding_value)) {
                $_default_template[$v] = $_overriding_value;
            }
        }

        $this->_twig_environment = new \Twig_Environment(
            new \Twig_Loader_Array(
                $_default_template
            )
        );
    }

    /**
     * @param array $options
     * @param array $attributes
     * @param string $selectedValue
     * @param string $templateToBeRendered
     *
     * @return bool|string
     */
    public function generateSelectElement(array $options = array(), array $attributes = array(), $selectedValue = "", $templateToBeRendered = 'html_element_template')
    {
        $_options_html = "";

        foreach($options as $_option => $_val)
        {
            $_attributes = array("value" => $_val);

            if($_val == $selectedValue) {
                $_attributes['selected'] = "true";
            }

            $_options_html .= $this->generateElement(
                'option',
                $_attributes,
                $_val
            );
        }

        return $this->generateElement('select', $attributes, $_options_html, true, $templateToBeRendered);
    }

    /**
     * @param array $config
     * @return bool|string
     */
    public function generateElementWithArrayConfig(array $config = array())
    {
        if(empty($config)) {
            return "";
        }

        $_html_tag_name = isset($config['tagName']) ? $config['tagName'] : false;
        $_attributes = isset($config['attributes']) && is_array($config['attributes']) ? $config['attributes'] : array();
        $_html_tag_value = isset($config['value']) ? $config['value'] : "";
        $_html_is_closable = isset($config['isClosable']) ? $config['isClosable'] : true;
        $_element_to_be_rendered = isset($config['elementTwigTemplate']) ? $config['elementTwigTemplate'] : 'html_element_template';

        if(!empty($_html_tag_name)) {
            return $this->generateElement($_html_tag_name, $_attributes, $_html_tag_value, $_html_is_closable,$_element_to_be_rendered);
        }

        return "";
    }

    /**
     * @param $tagName
     * @param array $attributes
     * @param string $value
     * @param bool|true $isClosable
     * @param string $templateToBeRendered
     *
     * @return bool|string
     */
    public function generateElement($tagName, array $attributes = array(), $value = "", $isClosable = true, $templateToBeRendered = 'html_element_template')
    {
        $cleanAttributes = $this->cleanUpAttributeValues($attributes);

        if ($this->_twig_environment instanceof \Twig_Environment) {
            return $this->_twig_environment->render($templateToBeRendered, array(
                'elTag' => trim($tagName),
                'attributes' => $cleanAttributes,
                'elTagClosable' => $isClosable,
                'elDataValue' => $value
            ));
        }

        return false;
    }

    /**
     * @param BeanInterface $bean
     * @param ColumnInterface $columnDefinition
     * @return mixed
     */
    public function generateElementByColumn(BeanInterface $bean, ColumnInterface $columnDefinition) {
        $_column_display_attributes = $columnDefinition->getHtmlElementType();

        if(isset($_column_display_attributes['elTag']))
        {
            $_column = $columnDefinition->getName();
            $_attributes = (isset($_column_display_attributes['attributes']) && is_array($_column_display_attributes['attributes'])) ? $_column_display_attributes['attributes'] : array();

            if($_column_display_attributes['elTag'] == 'select') {
                $_attributes = array_merge(array(
                    'id' => $bean->getName().'_'.$columnDefinition->getName(),
                    'name' => "PyntaxDAO[{$bean->getName()}][{$_column}]",
                    'class' => 'form-control'
                ), $_attributes);

                return $this->generateSelectElement($_column_display_attributes['options'], $_attributes , $bean->$_column);
            }

            return $this->generateElement($_column_display_attributes['elTag'] , array_merge(array(
                'id' => $bean->getName().'_'.$columnDefinition->getName(),
                'name' => "PyntaxDAO[{$bean->getName()}][{$_column}]",
                'class' => 'form-control'
            ), $_attributes), $bean->{$_column}, !($_column_display_attributes['elTag'] == "input"));
        }


        return "";
    }
}