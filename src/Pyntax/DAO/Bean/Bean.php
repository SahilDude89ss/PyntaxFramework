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
 * FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Pyntax\DAO\Bean;

use Pyntax\Config\Config;
use Pyntax\DAO\Bean\Column\ColumnInterface;
use Pyntax\PyntaxDAO;

/**
 * Class Bean
 * @package Pyntax\DAO\Bean
 */
class Bean extends BeanAbstract
{
    public function getPrimaryKey()
    {
        return $this->_primary_key;
    }

    public function save()
    {
        $primaryKeyValue = $this->__get($this->_primary_key);

        if (empty($primaryKeyValue) && !empty($this->_columns))
        {
            if ($this->validateColumns())
            {
                $id = $this->_db_adapter->Insert($this->_table_name, $this->_columns);
                $this->__set($this->_primary_key, $id);
                return $id;
            }

        } else {
            return $this->_db_adapter->Update($this->_table_name, $this->_columns, array($this->_primary_key => $primaryKeyValue));
        }

        return false;
    }

    public function delete()
    {
        $primaryKeyValue = $this->__get($this->_primary_key);
        if (!empty($primaryKeyValue)) {
            return $this->_db_adapter->Delete($this->_table_name, array($this->_primary_key => $primaryKeyValue));
        }

        return false;
    }

    /**
     * @ToDo: Load any related fields as beans.
     * This function is used to find data from the database.
     *
     * @param bool|false $searchCriteria
     * @param bool|true $returnArray
     *
     * @return Bean
     */
    public function find($searchCriteria = false, $returnArray = false)
    {
        $result = array();

        if (is_int($searchCriteria) || intval($searchCriteria) > 0) {
            $primaryKeyValueForSearch = intval($searchCriteria);
            $result = $this->_db_adapter->getOneResult($this->_table_name, array($this->_primary_key => $primaryKeyValueForSearch));
        } else if (is_array($searchCriteria) && !empty($searchCriteria)) {
            $limit = isset($searchCriteria['limit']) ? $searchCriteria['limit'] : 0;
            unset($searchCriteria['limit']);
            $result = $this->_db_adapter->Select($this->_table_name, $searchCriteria, $limit);
        } else if(empty($searchCriteria) || !$searchCriteria) {
            $result = $this->_db_adapter->Select($this->_table_name);
        }

        if($returnArray) {
            return $result;
        }

        return $this->convertSearchResultIntoBean($result);
    }

    public function loadRelatedBeanData() {
        if($this->getConfigForElement($this->_bean_config,'load_related_beans',$this->_table_name))
        {
            if(is_array($this->getForeignKeys()) && $this->_depth_counter < 1 )
            {
                foreach($this->getForeignKeys() as $_foreign_key)
                {
                    $this->_depth_counter++;
                    $_foreign_bean = PyntaxDAO::getBean($_foreign_key['foreign_key_table']); //, intval($_foreign_key['id'])
                    $_foreign_bean->setDepthCounter($this->_depth_counter);
                    $_result_bean = $_foreign_bean->find($this->__get($_foreign_key['column_name']));

                    if(!empty($_result_bean)) {
                        $this->{$_foreign_key['column_name']} = $_result_bean;
                    }
                }
            }
        }
    }

    /**
     * @param string $view
     * @return array
     */
    public function getDisplayColumns($view = 'list')
    {
        $_display_columns = array();

        if(isset(Config::$_config['orm']['beans'][$this->_table_name]))
        {
            if(isset(Config::$_config['orm']['beans'][$this->_table_name]['visible_columns'][$view]))
            {
                $_display_columns = Config::$_config['orm']['beans'][$this->_table_name]['visible_columns'][$view];
            }
        }

        if(empty($_display_columns)) {
            $_display_columns = array_keys($this->_columns);
        }

        $_columns_displayed = array();

        foreach($_display_columns as $_column_name)
        {
            if(isset($this->_column_definitions[$_column_name]))
            {
                $_column = $this->_column_definitions[$_column_name];

                if($_column instanceof ColumnInterface)
                {
                    if($_column->isColumnVisible())
                    {
                        $_columns_displayed[] = $_column;
                    }
                }
            }
        }

        return $_columns_displayed;
    }

    /**
     * @return array
     */
    public function getColumns() {
        return $this->_columns;
    }

    /**
     * @return bool|false|String
     */
    public function getName() {
        return $this->_table_name;
    }

    /**
     * @return array
     */
    public function getColumnDefinition()
    {
        return $this->_column_definitions;
    }

    public function findQuery($queryString)
    {
        return $this->_db_adapter->exec($queryString);
    }

    public function getForeignKeys() {
        return $this->_foreign_keys;
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    function __toString()
    {
        return $this->{$this->_primary_key};
    }


}