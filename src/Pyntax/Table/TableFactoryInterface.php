<?php
/**
 * Created by PhpStorm.
 * User: sahil
 * Date: 24/08/15
 * Time: 7:40 PM
 */

namespace Pyntax\Table;

use Pyntax\DAO\Bean\BeanInterface;

interface TableFactoryInterface
{
    /**
     * @param BeanInterface $bean
     * @param string $findCondition
     * @param bool|false $returnString
     *
     * @return mixed
     */
    public function generateTable(BeanInterface $bean, $findCondition = "", $returnString = false);
}