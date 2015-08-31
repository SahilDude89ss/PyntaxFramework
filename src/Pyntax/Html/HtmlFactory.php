<?php
namespace Pyntax\Html;

use Pyntax\DAO\Bean\BeanInterface;
use Pyntax\Html\Form\FormFactoryInterface;
use Pyntax\Html\Table\TableFactoryInterface;

/**
 * Class HtmlFactory
 * @package Pyntax\Html
 */
class HtmlFactory extends HtmlFactoryAbstract
{

    /**
     * @param BeanInterface $bean
     * @param string $findCondition
     * @return bool|string
     */
    public function createTable(BeanInterface $bean, $findCondition = "") {
        if($this->_table_factory instanceof TableFactoryInterface) {
            return $this->_table_factory->generateTable($bean, $findCondition, true);
        }

        return false;
    }

    /**
     * @param BeanInterface $bean
     * @return bool|mixed
     */
    public function createForm(BeanInterface $bean) {
        if($this->_html_factory instanceof FormFactoryInterface) {
            return $this->_html_factory->generateForm($bean, true);
        }

        return false;
    }
}