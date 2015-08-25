<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 25/08/15
 * Time: 2:42 PM
 */

namespace Pyntax\Form;


use Pyntax\DAO\Bean\BeanInterface;

class FormFactory extends FormFactoryAbstract
{
    /**
     * @param BeanInterface $bean
     * @param bool|false $id
     * @param bool|false $returnString
     *
     * @return mixed
     */
    public function generateForm(BeanInterface $bean, $id = false, $returnString = false)
    {
        // TODO: Implement generateForm() method.
    }

}