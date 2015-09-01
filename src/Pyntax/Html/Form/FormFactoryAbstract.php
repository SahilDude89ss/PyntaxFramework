<?php

namespace Pyntax\Html\Form;

use Pyntax\DAO\Bean\BeanInterface;
use Pyntax\Html\Element\ElementFactory;

/**
 * Class FormFactory
 * @package Pyntax\Form
 */
abstract class FormFactoryAbstract extends ElementFactory implements FormFactoryInterface
{
    /**
     * @var array
     */
    static protected $_bean_meta_data = array();

    /**
     * @param BeanInterface $bean
     */
    public function setBeanMetaData(BeanInterface $bean)
    {
        if (!isset(self::$_bean_meta_data[$bean->getName()])) {
            self::$_bean_meta_data[$bean->getName()] = $bean;
        }
    }

    /**
     * @param null $_bean_name
     * @return array
     */
    public function getDisplayColumns($_bean_name = null)
    {
        if (!empty($_bean_name)) {
            $bean = $this->getBeanFromMetaData($_bean_name);
            if($bean instanceof BeanInterface) {
                return $bean->getDisplayColumns();
            }
        }

        return array();
    }

    public function generateFormHtml($_bean_name = "") {
        $_fields_html_array = array();

        $_fields_to_be_displayed =  $this->getDisplayColumns($_bean_name);

        if(!empty($_fields_to_be_displayed)) {
            foreach($_fields_to_be_displayed as $_field) {

            }
        }
    }

    /**
     * @param null $_bean_name
     * @return bool
     */
    protected function getBeanFromMetaData($_bean_name = null)
    {
        return isset(self::$_bean_meta_data[$_bean_name]) ? self::$_bean_meta_data[$_bean_name] : false;
    }

    /**
     * @param string $fieldName
     */
    protected function generateHtmlTemplateFieldContainer($fieldName = 'input') {

    }
}