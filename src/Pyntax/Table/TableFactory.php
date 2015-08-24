<?php
/**
 * Created by PhpStorm.
 * User: sahil
 * Date: 24/08/15
 * Time: 7:43 PM
 */

namespace Pyntax\Table;


use Pyntax\DAO\Bean\BeanInterface;

class TableFactory extends TableFactoryAbstract
{
    /**
     * @param BeanInterface $bean
     * @param string $findCondition
     * @param bool|false $returnString
     * @return string
     */
    public function generateTable(BeanInterface $bean, $findCondition = "", $returnString = false)
    {
        $result = array();
        $tableHtml = "";

        if (!empty($findCondition)) {
            $result = $bean->find($findCondition, true);
        }

        if (!empty($result)) {
            $tableHtml = $this->generateTableHtml($bean, $result);
        }

        if ($returnString) {
            return $tableHtml;
        }

        echo $tableHtml;
    }

}