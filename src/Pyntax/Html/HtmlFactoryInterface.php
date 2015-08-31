<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 31/08/15
 * Time: 4:30 PM
 */

namespace Pyntax\Html;


use Pyntax\DAO\Bean\BeanInterface;

interface HtmlFactoryInterface
{
    /**
     * @param BeanInterface $bean
     * @param string $findCondition
     *
     * @return mixed
     */
    public function createTable(BeanInterface $bean, $findCondition = "");

    /**
     * @param BeanInterface $bean
     *
     * @return mixed
     */
    public function createForm(BeanInterface $bean);
}