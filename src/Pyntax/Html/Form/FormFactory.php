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

/**
 * Class FormFactory
 * @package Pyntax\Html\Form
 */
class FormFactory extends FormFactoryAbstract
{
    public function __construct()
    {
        parent::__construct();
        $this->loadFormConfig();
    }

    /**
     * @param BeanInterface $bean
     * @param bool|false $returnString
     *
     * @return mixed
     */
    public function generateForm(BeanInterface $bean, $returnString = false)
    {
        $_columns_to_be_displayed = $bean->getDisplayColumns('form');

        $_form_fields = "";

        if (isset($this->_form_config['form_column'])) {
            $_form_column_config = $this->_form_config['form_column'];

            $nbr_of_columns = $_form_column_config['nbr_of_columns'];
            $_fields_in_a_column = array_chunk($_columns_to_be_displayed, count($_columns_to_be_displayed) / $nbr_of_columns);

            foreach ($_fields_in_a_column as $_fields) {
                $_form_fields .= $this->generateFormColumn($_fields, $bean);
            }
        }

        if (empty($_form_fields)) {
            $_form_fields = $this->generateFormColumn($_columns_to_be_displayed, $bean);
        }

        $_form_fields .= $this->generateElement('input', array('type' => 'hidden', 'name' => 'PyntaxDAO[BeanName]'), $bean->getName(), false);
        $_form_fields .= $this->generateElement('button', array('type' => 'Submit',), 'Save');

        $_element_html = $this->generateElement('form', array('id' => 'frm_' . $bean->getName(), 'method' => 'post', 'class' => 'form-horizontal'), $_form_fields, true);

        if ($returnString) {
            return $this->generateFormContainer($_element_html);
        } else {
            echo $this->generateFormContainer($_element_html);
        }
    }

    /**
     * This function generates a column for a FORM
     *
     * @param array $_columns_to_be_displayed
     * @param BeanInterface $bean
     *
     * @return string
     */
    public function generateFormColumn(array $_columns_to_be_displayed, BeanInterface $bean)
    {
        $_form_fields = "";

        foreach ($_columns_to_be_displayed as $_column) {
            $_field_html = "";

            if (isset($this->_form_config['showLabels']) && $this->_form_config['showLabels'] == true) {
                $_field_html = $this->generateElement('label', array(
                    'for' => "PyntaxDAO[{$bean->getName()}][{$_column}]"
                ), $this->convertColumnNameIntoLabel($_column), true);

                if (isset($this->_form_config['label_container']) && isset($this->_form_config['label_container']['tagName'])) {
                    $_label_element_container = $this->generateElement($this->_form_config['label_container']['tagName'],
                        isset($this->_form_config['label_container']['attributes']) && is_array($this->_form_config['label_container']['attributes']) ? $this->_form_config['label_container']['attributes'] : array(),
                        $_field_html
                    );

                    $_field_html = $_label_element_container;
                }
            }

            if (isset($this->_form_config['element_container']) && isset($this->_form_config['element_container']['tagName'])) {
                $_form_element_container = $this->generateElement($this->_form_config['element_container']['tagName'],
                    isset($this->_form_config['element_container']['attributes']) && is_array($this->_form_config['element_container']['attributes']) ? $this->_form_config['element_container']['attributes'] : array(),
                    $this->generateElement('input', array(
                        'type' => 'text',
                        'id' => 'id_' . $_column,
                        'name' => "PyntaxDAO[{$bean->getName()}][{$_column}]",
                        'placeholder' => $_column,
                        'class' => 'form-control'
                    ), $bean->$_column, false)
                );

                $_field_html .= $_form_element_container;
            } else {
                $_field_html .= $this->generateElement('input', array(
                    'type' => 'text',
                    'id' => 'id_' . $_column,
                    'name' => "PyntaxDAO[{$bean->getName()}][{$_column}]",
                    'placeholder' => $_column,
                ), $bean->$_column, false);
            }


            $_form_element_container_config = isset($this->_form_config['form_element_container_template']) ? $this->_form_config['form_element_container_template'] : array();

            if (isset($_form_element_container_config['templateName']) && isset($_form_element_container_config['data']['tagName'])) {
                $_field_html = $this->generateElement($_form_element_container_config['data']['tagName'],
                    is_array($_form_element_container_config['data']['attributes']) ? $_form_element_container_config['data']['attributes'] : array(),
                    $_field_html, true, $_form_element_container_config['templateName']);
            }

            $_form_fields .= $_field_html;
        }


        if (isset($this->_form_config['form_column']['container_element_template']['tagName'])) {
            return $this->generateElement($this->_form_config['form_column']['container_element_template']['tagName'],
                isset($this->_form_config['form_column']['container_element_template']['attributes']) ? $this->_form_config['form_column']['container_element_template']['attributes'] : array(),
                $_form_fields, true);
        }

        return $_form_fields;
    }

    /**
     * @param $elData
     * @param array $attributes
     * @return bool|string
     */
    public function generateFormContainer($elData, $attributes = array())
    {
        $_form_container_template = isset($this->_form_config['from_container_template']) ? $this->_form_config['from_container_template'] : array();

        if (!empty($elData) && isset($_form_container_template['data']['tagName'])) {
            return $this->generateElement($_form_container_template['data']['tagName'],
                (isset($_form_container_template['data']['attributes']) && is_array($_form_container_template['data']['attributes'])) ? array_merge($_form_container_template['data']['attributes'], $attributes) : array()
                , $elData, true);
        }

        return $elData;
    }

    /**
     * @param $columnName
     * @return string
     */
    public function convertColumnNameIntoLabel($columnName)
    {
        if (isset($this->_form_config['convertColumnNamesIntoLabel']) && $this->_form_config['convertColumnNamesIntoLabel'] == true) {
            $_new_labels = str_replace(array("_", "-"), " ", $columnName);
            return (ucwords($_new_labels));
        }

        return $columnName;
    }
}