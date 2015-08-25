<?php

namespace Pyntax\Form;
use Pyntax\DAO\Bean\BeanInterface;

/**
 * Interface FormFactoryInterface
 * @package Pyntax\Form
 */
interface FormFactoryInterface
{
    /**
     * @param BeanInterface $bean
     * @param bool|false $id
     * @param bool|false $returnString
     *
     * @return mixed
     */
    public function generateForm(BeanInterface $bean, $id = false, $returnString = false);
}