<?php

namespace Pyntax\Html\Element;
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
                    'html_element_template' => "<{{elTag}} {% for attribute in attributes %}{{attribute.name}}='{{attribute.value}}'{% endfor %} {% if( elTagClosable == true) %}> {{elDataValue|raw}} </{{elTag}}>{% else %}value='{{elDataValue}}' />{% endif %}"
                )
            )
        );
    }

    /**
     * @param $tagName
     * @param array $attributes
     * @param string $value
     * @param bool|true $isClosable
     *
     * @return null|string
     */
    public function generateElementHtml($tagName, array $attributes = array(), $value = "", $isClosable = true)
    {
        $cleanAttributes = $this->cleanUpAttributeValues($attributes);

        if ($this->_twig_environment instanceof \Twig_Environment) {
            return $this->_twig_environment->render('html_element_template', array(
                'elTag' => $tagName,
                'attributes' => $cleanAttributes,
                'elTagClosable' => $isClosable,
                'elDataValue' => $value
            ));
        }

        return false;
    }

    /**
     *
     * @param Column $columnDefinition
     */
    public function generateElementByColumn(Column $columnDefinition) {
        var_dump($columnDefinition->getHtmlElementType());
    }
}