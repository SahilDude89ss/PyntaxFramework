<?php

namespace Pyntax\Table;

use Pyntax\Config\Config;
use Pyntax\DAO\Bean\BeanInterface;

/**
 * Class TableFactoryAbstract
 * @package Pyntax\Table
 */
abstract class TableFactoryAbstract implements TableFactoryInterface
{
    /**
     * @param BeanInterface $bean
     * @param string $findCondition
     * @param bool|false $returnString
     *
     * @return mixed
     */
    public abstract function generateTable(BeanInterface $bean, $findCondition = "", $returnString = false);

    /**
     * @param BeanInterface $bean
     * @param array $searchResults
     * @return string
     */
    public function generateTableHtml(BeanInterface $bean, $searchResults = array())
    {
        $table = $this->generateTableHeader($bean->getDisplayColumns());
        $table .= $this->generateTableBody($searchResults, $bean->getDisplayColumns());

        $_table_config = Config::readConfig('table_config');

        $_class = "";

        if(isset($_table_config['table']['class'])) {
            $_class = $_table_config['table']['class'];
        }

        return "<table class='{$_class}'>{$table}</table>";
    }

    /**
     * @param array $tableColumns
     * @return string
     */
    public function generateTableHeader($tableColumns = array())
    {
        $_table_header = "\n<thead>\n\t<tr>";

        if (!empty($tableColumns)) {
            foreach ($tableColumns as $tableColumn) {
                $_table_header .= $this->generateTH($tableColumn);
            }
        }

        $_table_header .= "\t\n</tr>\n</thead>";

        return $_table_header;
    }

    /**
     * @param array $tableData
     * @param array $visibleColumns
     *
     * @return string
     */
    public function generateTableBody(array $tableData = array(), $visibleColumns = array())
    {
        $_table_body = "<tbody>";

        if (!empty($tableData)) {
            foreach ($tableData as $_row) {
                $_table_body .= "<tr>";
                foreach ($visibleColumns as $key => $_column) {
                    $_table_body .= $this->generateTD($_row[$_column]);
                }
                $_table_body .= "</tr>";
            }
        }

        $_table_body .= "</tbody>";

        return $_table_body;
    }

    /**
     * @param $tdData
     * @return string
     */
    protected function generateTD($tdData)
    {
        return $this->generateElement('td', $tdData);
    }

    /**
     * @param $thData
     * @return string
     */
    protected function generateTH($thData)
    {
        return $this->generateElement('th', $thData, true);
    }

    /**
     * @param $elTag
     * @param $elData
     * @param $replaceNonStringCharacters
     * @return string
     */
    protected function generateElement($elTag, $elData, $replaceNonStringCharacters = false)
    {
        $_th_data = $elData;
        $_class = "";
        $_id = "";

        if (is_array($elData) && isset($elData['data'])) {
            if (isset($thData['class']) && !empty($thData['class'])) {
                $_class = $thData['class'];
            }

            if (isset($thData['id']) && !empty($thData['id'])) {
                $_id = $thData['id'];
            }
        }

        if (empty(trim($_id))) {
            $_id = md5(time()) . "_" . $elTag;
        }

        if ($replaceNonStringCharacters) {
            $_th_data = strtoupper(str_replace("_", " ", $_th_data));
        }

        return "<{$elTag} class='{$_class}' id='{$_id}'>{$_th_data}</{$elTag}>";
    }
}