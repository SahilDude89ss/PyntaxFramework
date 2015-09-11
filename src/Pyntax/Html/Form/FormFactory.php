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

namespace Pyntax\Html\Form;
use Pyntax\Config\Config;
use Pyntax\DAO\Bean\BeanInterface;
use Pyntax\Html\Element\ElementFactory;

/**
 * Class FormFactory
 * @package Pyntax\Html\Form
 */
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

        $_columns_to_be_displayed = $bean->getDisplayColumns('form');

        $_form_config = Config::readConfig('form');

        $_form_fields = "";

        foreach($_columns_to_be_displayed as $_column) {
            $_field_html = $elementFactory->generateElementHtml('label', array(
                'for' => "PyntaxDAO[{$bean->getName()}][{$_column}]"
            ), $_column, true);


            $_field_html .= $elementFactory->generateElementHtml('input', array(
                'type' => 'text',
                'id' => 'id_'.$_column,
                'name' => "PyntaxDAO[{$bean->getName()}][{$_column}]",
                'placeholder' => $_column,
            ), $bean->$_column, false);

            if(isset($_form_config['form_element_in_container_template']['templateName'])
                && isset($_form_config['form_element_in_container_template']['data']['tagName'])
                && is_array($_form_config['form_element_in_container_template']['data']['attributes']))
            {
                $_fields_with_label = $elementFactory->generateElementHtml($_form_config['form_element_in_container_template']['data']['tagName'],
                    is_array($_form_config['form_element_in_container_template']['data']['attributes']) ? $_form_config['form_element_in_container_template']['data']['attributes'] : array(),
                $_field_html, true, $_form_config['form_element_in_container_template']['templateName']);

                $_field_html = $_fields_with_label;
            }

            $_form_fields .= $_field_html;
        }

        $_form_fields .= $elementFactory->generateElementHtml('input', array(
            'type' => 'hidden',
            'name' => 'PyntaxDAO[BeanName]'
        ), $bean->getName(), false);

        $_form_fields .= $elementFactory->generateElementHtml('button', array(
            'type' => 'Submit',
        ), 'Save');

        $_element_html = $elementFactory->generateElementHtml('form', array(
            'id' => 'frm_'.$bean->getName(),
            'method' => 'post'
        ), $_form_fields, true);

        if($returnString) {
            return $_element_html;
        } else {
            echo $_element_html;
        }
    }
}