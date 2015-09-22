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

use Pyntax\Config\Config;
use Pyntax\DAO\Bean\Bean;
use Pyntax\DAO\Bean\Column\Column;

/**
 * Class ElementFactory
 * @package Pyntax\Html\Element
 */
class ElementFactory extends ElementFactoryAbstract
{
    /**
     * @var bool
     */
    protected $_twig_environment = false;

    public function __construct()
    {
        if (!$this->_twig_environment) {
            $this->setupTwig();
        }
    }

    protected function setupTwig()
    {
        $_default_template = array(
            'html_element_template' => "<{{elTag}} {% for attribute in attributes %}{{attribute.name}}='{{attribute.value}}'{% endfor %} {% if( elTagClosable == true) %}> {{elDataValue|raw}} </{{elTag}}>{% else %}value='{{elDataValue}}' />{% endif %}",
        );

        $loadTemplatesFromConfig = Config::readConfig('template');

        if(!empty($loadTemplatesFromConfig) && is_array($loadTemplatesFromConfig)) {
            if(sizeof($loadTemplatesFromConfig) > 0) {
                $loadTemplatesFromConfig = array_merge($_default_template, $loadTemplatesFromConfig);
            }
        } else {
            $loadTemplatesFromConfig = $_default_template;
        }

        $this->_twig_environment = new \Twig_Environment(
            new \Twig_Loader_Array(
                $loadTemplatesFromConfig
            )
        );
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
     * @param Bean $bean
     * @param Column $columnDefinition
     *
     * @return bool|string
     */
    public function generateElementByColumn(Bean $bean, Column $columnDefinition) {
        $_column_display_attributes = $columnDefinition->getHtmlElementType();

        if(isset($_column_display_attributes['elTag']) && isset($_column_display_attributes['attributes'])) {
            $_column = $columnDefinition->getName();
            $_attributes = is_array($_column_display_attributes['attributes']) ? $_column_display_attributes['attributes'] : array();

            return $this->generateElementHtml($_column_display_attributes['elTag'] , array_merge(array(
                'type' => 'text',
                'id' => $bean->getName().'_'.$columnDefinition->getName(),
                'name' => "PyntaxDAO[{$bean->getName()}][{$_column}]"
            ), $_attributes), $bean->$_column, false);
        }
    }
}