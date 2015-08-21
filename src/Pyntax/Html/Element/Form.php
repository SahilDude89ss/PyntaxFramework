<?php

namespace Pyntax\Html\Element;

/**
 * Attribute	Description
 * accept-charset	Specifies the charset used in the submitted form (default: the page charset).
 * action	Specifies an address (url) where to submit the form (default: the submitting page).
 * autocomplete	Specifies if the browser should autocomplete the form (default: on).
 * enctype	Specifies the encoding of the submitted data (default: is url-encoded).
 * method	Specifies the HTTP method used when submitting the form (default: GET).
 * name	Specifies a name used to identify the form (for DOM usage: document.forms.name).
 * novalidate	Specifies that the browser should not validate the form.
 * target	Specifies the target of the address in the action attribute (default: _self).
 *
 * Class Form
 * @package Pyntax\Html\Element
 */
class Form extends Element
{
    const METHOD_TYPE_GET = 'GET';

    const METHOD_TYPE_POST = 'POST';

    /**
     * @var array
     */
    protected $_default_attributes = array(
        'accept-charset' => '',
        'action' => '',
        'autocomplete' => '',
        'enctype' => '',
        'method' => self::METHOD_TYPE_GET,
        'name' => '',
        'novalidate' => '',
        'target' => ''
    );

    public function __construct($name, array $attributes = array()) {
        parent::__construct('form', true);
        $this->addAttributes($attributes);
    }
}