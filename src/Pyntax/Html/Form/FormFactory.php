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

namespace Pyntax\Html\Form;

use Pyntax\Config\Config;
use Pyntax\DAO\Bean\BeanInterface;
use Pyntax\DAO\Bean\Column\ColumnInterface;
use Pyntax\Pyntax;

/**
 * Class FormFactory
 * @package Pyntax\Html\Form
 */
class FormFactory extends FormFactoryAbstract
{
    /**
     * @var string
     */
    protected $_form_template_key = 'formTemplate';

    public function __construct()
    {
        parent::__construct();
        $this->setModuleConfigKey('form');

        /**
         * If the setting are on to save the bean the save the data.
         */
        $this->saveBean();
    }

    /**
     * @ToDo: Add calculated fields
     * @ToDo: Add callbacks on beforeSave and afterSave
     *
     * This function will load all the columns to be displayed for a Bean. These columns can be defined in
     * form.config.php with the following settings. The settings should be added to
     *
     * 'beans' => array(
     *  '<Table_Name>' => array( <=
     *      'visible_columns' => array(
     *          'orm' => array(
     *              <List_Of_all_the_Columns_as_an_Array>
     *          )
     *      )
     *  )
     *
     * @param BeanInterface $bean
     * @param bool|false $returnString
     *
     * @return mixed
     */
    public function generateForm(BeanInterface $bean, $returnString = false)
    {
        //Get all the colums that can be displayed for a the current bean
        $_columns_to_be_displayed = $bean->getDisplayColumns('form');

        $_form_fields = "";

        $_form_column = Config::read(array('form','form_column'));

        //Check if the form is supposed to tbe borken into columns
        if (isset($_form_column)) {
            $nbr_of_columns = $_form_column['nbr_of_columns'];

            $_fields_in_a_column = array_chunk($_columns_to_be_displayed, count($_columns_to_be_displayed) / $nbr_of_columns);

            foreach ($_fields_in_a_column as $_fields) {
                $_form_fields .= $this->generateFormColumn($_fields, $bean);
            }
        }

        //If the form were not broken into columns.
        if (empty($_form_fields)) {
            $_form_fields = $this->generateFormColumn($_columns_to_be_displayed, $bean);
        }

        //Only add the hidden field if the Saving the Bean is turned on in Config
        if ($this->getConfigForElement(Config::read('form'), 'capturePostAndSaveBean', $bean->getName(), 'beans')) {
            $_form_fields .= $this->generateElement('input', array('type' => 'hidden', 'name' => 'PyntaxDAO[BeanName]'), $bean->getName(), false);
        }

        //Generate the save button
        $_form_fields .= $this->generateSubmitButton($bean);

        $_form_template_generate_form_config = Config::read($this->_form_template_key);
        //Load the config for the form template and generate the form
        if (isset($_form_template_generate_form_config)) {

            //Load the form config, with overrides for the Bean
            $_tmp_form_config = $this->getConfigForElement(Config::read('form'), $this->_form_template_key, $bean->getName(), 'beans');

            //If the id is not set in the attribute then set the ID for the Bean::getName()
            if (!isset($_tmp_form_config['attributes']['id'])) {
                $_tmp_form_config['attributes']['id'] = 'frm_' . $bean->getName();
            }

            //Add the fields that were generated as the content of the forms.
            $_tmp_form_config['value'] = $_form_fields;

            //Generate the HTML for the form
            $_element_html = $this->generateElementWithArrayConfig($_tmp_form_config);
        } else {
            // A Default fall back if the config goes missing.
            $_element_html = $this->generateElement('form', array('id' => 'frm_' . $bean->getName(), 'method' => 'post', 'class' => 'form-horizontal', 'action' => '#'.time()), $_form_fields, true);
        }

        //Return the HTML as string
        if ($returnString) {
            return $_element_html;
        }

        //Print the HTML out
        echo $_element_html;
    }

    /**
     * @param BeanInterface $bean
     * @return bool|string
     */
    public function generateSubmitButton(BeanInterface $bean)
    {
        $_submit_button_config = $this->getConfigForElement(Config::read('form'), 'submitButton', $bean->getName(), 'beans');

        if (is_array($_submit_button_config)) {
            return $this->generateElementWithArrayConfig($_submit_button_config);
        }

        return $this->generateElement('button', array('type' => 'Submit',), 'Save ' . $bean->getName());
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
            if ($_column instanceof ColumnInterface) {
                $_field_html = "";

                $_show_label = $this->getConfigForElement(Config::read('form'), 'showLabels', $bean->getName(), 'beans');

                if ($_show_label) {
                    $_field_html = $this->generateElement('label', array(
                        'for' => "PyntaxDAO[{$bean->getName()}][{$_column->getName()}]"
                    ), $this->convertColumnNameIntoLabel($_column->getName()), true);

                    $_label_container = Config::read(array('form','label_container'));
                    if (isset($_label_container) && isset($_label_container['tagName'])) {
                        $_label_element_container = $this->generateElement($_label_container['tagName'],
                            isset($_label_container['attributes']) && is_array($_label_container['attributes']) ?$_label_container['attributes'] : array(),
                            $_field_html
                        );

                        $_field_html = $_label_element_container;
                    }
                }

                $_element_container = Config::read(array('form','element_container'));
                if (isset($_element_container) && isset($_element_container['tagName'])) {

                    $_element_html = $this->generateElementByColumn($bean, $_column);

                    $_form_element_container = $this->generateElement($_element_container['tagName'],
                        isset($_element_container['attributes']) && is_array($_element_container['attributes']) ? $_element_container['attributes'] : array(),
                        $_element_html
                    );

                    $_field_html .= $_form_element_container;
                } else {
                    $_field_html .= $this->generateElementByColumn($bean, $_column);
                }


                $_form_element_container_config = Config::read(array('form','form_element_container_template'));

                if (isset($_form_element_container_config['templateName']) && isset($_form_element_container_config['data']['tagName'])) {
                    $_field_html = $this->generateElement($_form_element_container_config['data']['tagName'],
                        is_array($_form_element_container_config['data']['attributes']) ? $_form_element_container_config['data']['attributes'] : array(),
                        $_field_html, true, $_form_element_container_config['templateName']);
                }

                $_form_fields .= $_field_html;
            }
        }

        $_primary_key_value = $bean->{$bean->getPrimaryKey()};

        if(!empty($_primary_key_value)) {
            $_form_fields.= $this->generateElement('input',array('type' => 'hidden', 'name' => "PyntaxDAO[{$bean->getName()}][{$bean->getPrimaryKey()}]"), $_primary_key_value, false);
        }

        $_form_column =  Config::read(array('form','form_column'));
        if (isset($_form_column['container_element_template']['tagName'])) {
            return $this->generateElement($_form_column['container_element_template']['tagName'],
                isset($_form_column['container_element_template']['attributes']) ? $_form_column['container_element_template']['attributes'] : array(),
                $_form_fields, true);
        }

        return $_form_fields;
    }

    /**
     * @return bool|BeanInterface
     */
    public function saveBean()
    {
      //        if (isset($this->_form_config['capturePostAndSaveBean']) && $this->_form_config['capturePostAndSaveBean'] == true) {
        if(Config::read(array('form', 'capturePostAndSaveBean'))) {
            $beanName = isset($_POST['PyntaxDAO']['BeanName']) ? $_POST['PyntaxDAO']['BeanName'] : false;

            if ($beanName && isset($_POST['PyntaxDAO'][$beanName])) {

                $_data = $_POST['PyntaxDAO'][$beanName];

                $beforeSaveCallBack = $this->getConfigForElement(Config::read('form'), 'callback_before_capturePostAndSaveBean', $beanName, 'beans');
                if (is_callable($beforeSaveCallBack)) {
                    $_data = $beforeSaveCallBack($_POST['PyntaxDAO'][$beanName]);
                }

                if(empty($_data)) {
                    $_data =  $_POST['PyntaxDAO'][$beanName];
                }

                $bean = Pyntax::getBean($beanName);
                foreach ($_data as $key => $val) {
                    $bean->$key = $val;
                }

                $id = $bean->save();

                $afterSaveCallback = $this->getConfigForElement(Config::read('form'), 'callback_after_capturePostAndSaveBean', $beanName, 'beans');

                if (is_callable($afterSaveCallback)) {
                    $afterSaveCallback($bean, $id);
                }

                return $bean;
            }
        }

        return false;
    }
}