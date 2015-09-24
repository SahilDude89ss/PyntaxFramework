<?php
Pyntax\Config\Config::writeConfig('form', array(
    /**
     * @property: capturePostAndSaveBean
     * This allows the bean to be saved into the database when a form is submitted.
     */
    'capturePostAndSaveBean' => true,

    /**
     * @property: showLabels
     * This is a  flag used to display the labels when a form is rendered
     */
    'showLabels' => true,

    /**
     * @property: convertColumnNamesIntoLabel
     * This allows the labels on the field to be formatted automatically depending on the field name. It removes
     * any "_" and "-" with a space and makes all the first alphabets of the words upper case.
     */
    'convertColumnNamesIntoLabel' => true,

    /**
     * @property: submitButton
     * This defines the template for Submit button that will be rendered in a form.
     * A Custom button can be defined in the beans key in the config array.
     *
     * - <TABLE_NAME> will be replacted with the table name in the Bean
     */
    'submitButton' => array(
        'tagName' => 'button',
        'attributes' => array(
            'type' => 'submit'
        ),
        'value' => 'Save'
    ),

    /**
     * @property: beans
     * This keys stores custom config for forms for particular beans.
     */
    'beans' => array(),

    /**
     * @property: formTemplate
     * This defines a default form tag with the defaults attributes
     */
    'formTemplate' => array(
        'tagName' => 'form',
        'attributes' => array(
            'method' => 'post',
            'class' => 'form-horizontal'
        )
    ),

    /**
     * @property: labelTempalte
     * This defines a default label tag with the default attributes
     */
    'labelTemplate' => array(
        'tagName' => 'label',
    ),

    /**
     * Defining callback functions on element rendering
     * Components in a Form:
     * 1. All the html elements
     * 2. Html element containers.
     *
     * Defining a container
     * 1. <HTML_Element_NAME>_container
     */
    'label_container' => array(
        'tagName' => 'div',
        'attributes' => array(
            'class' => 'col-sm-2 control-label'
        )
    ),
    'element_container' => array(
        'tagName' => 'div',
        'attributes' => array(
            'class' => 'col-sm-10'
        )
    ),
//    'from_container_template' => array(
//        'data' => array(
//            'tagName' => 'div',
//            'attributes' => array(
//                'class' => 'box box-primary'
//            )
//        )
//    ),
    'form_column' => array(
        'nbr_of_columns' => 2,
        'column_element_template' => array(
            'tagName' => ' div',
            'attributes' => array(
                'class' => 'row'
            )
        ),
        'container_element_template' => array(
            'tagName' => 'div',
            'attributes' => array(
                'class' => 'col-md-6'
            )
        ),

    ),
    'form_element_container_template' => array(
        'templateName' => 'html_element_template',
        'data' => array(
            'tagName' => 'div',
            'attributes' => array(
                'class' => 'form-group'
            )
        ),
    ),
    'directories_to_be_scanned' => array(
        'loadFilesAutomatically' => true,
        'css' => array(
            './third-party/AdminLTE-2.3.0/dist/css'
        ),
        'js' => array(
            './third-party/AdminLTE-2.3.0/dist/js'
        )
    )
));