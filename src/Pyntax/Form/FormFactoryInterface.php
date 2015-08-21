<?php

namespace Pyntax\Form;

/**
 * Interface FormFactoryInterface
 * @package Pyntax\Form
 */
interface FormFactoryInterface
{
    public function generateForm($returnString = false);
}