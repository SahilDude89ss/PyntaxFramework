<?php

namespace Pyntax\Html\Element;

/**
 * Class Input
 * @package Pyntax\Html\Element
 */
class Input extends Element
{
    /**
     * @var array
     */
    protected $allowedTypes = array(
        "button", "checkbox", "color", "date", "datetime", "datetime-local", "email", "file", "hidden", "image",
        "month", "number", "password", "radio", "range", "reset", "search", "submit", "tel", "text", "time", "url",
        "week",
    );

    /**
     * @param $tableElementName
     */
    public function __construct($tableElementName)
    {
        if(!parent::__construct($tableElementName)) {
            return false;
        }
    }
}