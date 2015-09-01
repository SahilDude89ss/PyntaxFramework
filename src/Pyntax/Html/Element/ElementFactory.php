<?php

namespace Pyntax\Html\Element;
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
        $this->_twig_environment = new \Twig_Environment(
            new \Twig_Loader_Array(
                array(
                    'html_element_template' => "<{{elTag}} {% for attribute in attributes %}{{attribute.name}}='{{attribute.value}}'{% endfor %} {% if( elTagClosable == true) %}> {{elDataValue|raw}} </{{elTag}}>{% else %}value='{{elDataValue}}' />{% endif %}",
                )
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
    public function generateElementHtml($tagName, array $attributes = array(), $value = "", $isClosable = true, $templateToBeRendered = 'html_element_template')
    {
        $cleanAttributes = $this->cleanUpAttributeValues($attributes);

        if ($this->_twig_environment instanceof \Twig_Environment) {
            return $this->_twig_environment->render($templateToBeRendered, array(
                'elTag' => $tagName,
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