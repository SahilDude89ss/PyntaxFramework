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

namespace Pyntax\DAO\Bean;

use Aura\SqlQuery\Exception;
use Pyntax\Config\Config;
use Pyntax\DAO\Bean\Column\Column;
use Pyntax\DAO\Adapter\AdapterInterface;
use Pyntax\DAO\Bean\Column\ColumnInterface;

/**
 * Class Bean
 * @package Pyntax\DAO\Bean
 */
class Bean extends Config implements BeanInterface
{
    /**
     * @var array
     */
    protected $_columns = array();

    /**
     * @var array
     */
    protected $_column_definitions = array();

    /**
     * @var array
     */
    protected $_indexes = array();

    /**
     * @var array
     */
    protected $_indexes_definitions = array();

    /**
     * @var bool|AdapterInterface
     */
    protected $_db_adapter = false;

    /**
     * @var bool | String
     */
    protected $_table_name = false;

    /**
     * @var bool | String
     */
    protected $_primary_key = false;


    /**
     * @param bool|false $tableName
     * @param AdapterInterface|null $adapter
     */
    public function __construct($tableName = false, AdapterInterface $adapter = null)
    {
        if (!$tableName || is_null($adapter)) {
            return false;
        }

        $this->_db_adapter = $adapter;
        $this->_table_name = $tableName;

        $this->loadMetaData();
    }

    /**
     * @param array $metaData
     * @return bool
     */
    private function processMetaData(array $metaData = null)
    {
//        $_orm_config = Config::readConfig('orm');
//
////        if(isset($_orm_config['load_related_beans']) && $_orm_config['load_related_beans'] == true) {
////            $foreignKeys = $this->_db_adapter->getForeignKeys($this->_table_name);
////        }

        if (is_null($metaData)) {
            return false;
        }
        if (isset($metaData['Fields'])) {
            $this->setColumnDefinition($metaData['Fields']);
        }

        if (isset($metaData['Indexes'])) {
            $this->setIndexesDefinitions($metaData['Indexes']);
        }
    }

    /**
     * @param array $indexesMetaData
     * @return bool
     */
    private function setIndexesDefinitions($indexesMetaData = array())
    {
        if (is_null($indexesMetaData)) {
            return false;
        }

        //Process all the Primary Keys/Composite keys
        foreach ($indexesMetaData as $indexMetaData) {
            if ($indexMetaData['Table'] == $this->_table_name && $indexMetaData['Key_name'] == "PRIMARY") {
                $this->setPrimaryKeyDefinition($indexMetaData['Column_name']);
            }
        }
    }

    /**
     * @param bool|false $primaryKey
     * @return bool
     */
    private function setPrimaryKeyDefinition($primaryKey = false)
    {
        if (!$primaryKey) {
            return false;
        }

        if (empty($this->_primary_key)) {
            $this->_primary_key = $primaryKey;
            return true;
        } else {
            if (is_array($this->_primary_key)) {
                $this->_primary_key[] = $primaryKey;
                return true;
            } else if (is_string($this->_primary_key)) {
                $tmpFirstPrimaryKey = $this->_primary_key;
                $this->_primary_key = array();
                $this->_primary_key[] = $tmpFirstPrimaryKey;
                $this->_primary_key[] = $primaryKey;
                return true;
            }
        }

        return false;
    }

    /**
     * @param array|null $fieldsMetadata
     * @return bool
     */
    private function setColumnDefinition(array $fieldsMetadata = null)
    {
        if (is_null($fieldsMetadata)) {
            return false;
        }

        foreach ($fieldsMetadata as $colDefinition) {
            if (isset($colDefinition['Field'])) {
                $this->_column_definitions[$colDefinition['Field']] = new Column($colDefinition);
                $this->_columns[$colDefinition['Field']] = "";
            }
        }
    }

    /**
     * @ToDo: Load meta data form Config
     */
    private function loadMetaData()
    {
        $this->processMetaData($this->_db_adapter->getMetaData($this->_table_name));
    }

    /**
     * @param $name
     * @return bool
     */
    function __get($name)
    {
        return (isset($this->_columns[$name]) ? $this->_columns[$name] : false);
    }

    /**
     * @param $name
     * @param $value
     *
     * @return bool
     */
    function __set($name, $value)
    {
        if (isset($this->_columns, $name)) {
            $this->_columns[$name] = $value;
        }
    }

    function getPrimaryKey()
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

        if (is_int($searchCriteria)) {
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
            $_display_columns = $this->_column_definitions;
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
     * @param array $resultArray
     * @return $this|array|bool
     */
    protected function convertSearchResultIntoBean(array $resultArray)
    {
        if (!empty($resultArray)) {
            //Check if the count is more than 1 then its a list search
            if (count($resultArray) == 1) {
                $_rowData = $resultArray[0];

                foreach ($_rowData as $key => $val) {
                    $this->__set($key, $val);
                }

                return $this;
            } else {
                $returnResults = array();
                foreach ($resultArray as $_row) {
                    //The object is being cloned because this will save use from initiating another object of the same
                    //Bean, which means it will load the data either from the db or cache.
                    $clonedObject = clone $this;
                    $returnResults[] = $clonedObject->convertSearchResultIntoBean(array($_row));
                }

                return $returnResults;
            }
        }

        return false;
    }

    /**
     * @ToDo: Implement Validation depending on the table validation
     * @return bool
     */
    protected function validateColumns() {
        return true;
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
}