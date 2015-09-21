<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2015 Sahil Sharma (SahilDude89ss@gmail.com)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Pyntax\Html\Table;
use Pyntax\Config\Config;
use Pyntax\DAO\Bean\BeanInterface;
use Pyntax\Html\Element\ElementFactory;

/**
 * Class TableFactoryAbstract
 * @package Pyntax\Table
 */
abstract class TableFactoryAbstract extends ElementFactory implements TableFactoryInterface
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

        $_table_config = Config::readConfig('table');

        $attributes = isset($_table_config['table']) && is_array($_table_config['table']) ? $_table_config['table'] : array();

        return $this->generateElementHtml('table', $attributes, $table);
    }

    /**
     * @param array $tableColumns
     * @return string
     */
    public function generateTableHeader($tableColumns = array())
    {
        $_table_header_data = "";

        if (!empty($tableColumns)) {
            foreach ($tableColumns as $tableColumn) {
                $_table_header_data .= $this->generateTH($tableColumn);
            }
        }

        $_table_header = $this->generateTR($_table_header_data);

        return $this->generateElementHtml('thead', array(), $_table_header, true);
    }

    /**
     * @param array $tableData
     * @param array $visibleColumns
     *
     * @return string
     */
    public function generateTableBody(array $tableData = array(), $visibleColumns = array())
    {
        $_table_body = "";

        if (!empty($tableData)) {
            foreach ($tableData as $_row) {
                $_tr_data = "";
                foreach ($visibleColumns as $key => $_column) {
                    $_tr_data .= $this->generateTD($_row[$_column]);
                }
                $_table_body .= $this->generateTR($_tr_data);
            }
        }

        return $this->generateElementHtml('tbody', array(), $_table_body, true);
    }

    /**
     * @param $rowData
     * @param array $rowAttributes
     * @return bool|string
     */
    protected function generateTR($rowData, $rowAttributes = array()) {
        return $this->generateElementHtml('tr', $rowAttributes, $rowData, true);
    }

    /**
     * @param $tdData
     * @param array $attributes
     * @return bool|string
     */
    protected function generateTD($tdData, $attributes = array())
    {
        return $this->generateElementHtml('td', $attributes, $tdData, true);
    }

    /**
     * @param $thData
     * @param array $attributes
     *
     * @return bool|string
     */
    protected function generateTH($thData, $attributes = array())
    {
        $thData = strtoupper(str_replace("_", " ", $thData));
        return $this->generateElementHtml('th', $attributes, $thData, true);
    }
}