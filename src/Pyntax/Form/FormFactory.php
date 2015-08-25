<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 25/08/15
 * Time: 2:42 PM
 */

namespace Pyntax\Form;


use Pyntax\DAO\Bean\BeanInterface;
use Pyntax\Html\Element\ElementFactory;

class FormFactory extends FormFactoryAbstract
{
    /**
     * @param BeanInterface $bean
     * @param bool|false $returnString
     *
     * @return mixed
     */
    public function generateForm(BeanInterface $bean, $returnString = false)
    {
        $elementFactory = new ElementFactory();

        $_columns_to_be_displayed = $bean->getDisplayColumns();

        $_form_fields = "";
        foreach($_columns_to_be_displayed as $_column) {
            $_form_fields .= $elementFactory->generateElementHtml('input', array(
                'type' => 'text',
                'id' => 'id_'.$_column,
                'name' => "PyntaxDAO[{$bean->getName()}][{$_column}]"
            ), $bean->$_column, false);
        }

        $_form_fields .= $elementFactory->generateElementHtml('button', array(
            'type' => 'Submit',
        ), 'Save');

        echo $elementFactory->generateElementHtml('form', array(
            'id' => 'frm_'.$bean->getName(),
            'method' => 'post'
        ), $_form_fields, true);
    }

}