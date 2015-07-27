<?php

namespace Pyntax\DAO\Bean;

use Aura\SqlQuery\Exception;
use Pyntax\Common\AdapterInterface;
use Pyntax\Common\BeanInterface;
use Pyntax\DAO\Bean\Column\Column;

/**
 * Class Bean
 * @package Pyntax\DAO\Bean
 */
class Bean implements BeanInterface
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
     * @param array $metaData
     */
    public function __construct($tableName = false, AdapterInterface $adapter = null, array $metaData = array())
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
                $this->_column_definitions[] = new Column($colDefinition);
                $this->_columns[$colDefinition['Field']] = "";
            }
        }
    }

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
        if(empty($this->__get($this->_primary_key)) && !empty($this->_columns))
        {
            return $this->_db_adapter->Insert($this->_table_name, $this->_columns);
        }
        else
        {
            die("Updated");
        }
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }

    /**
     * This function is used to find data from the database.
     *
     * @param bool|false $searchCriteria
     * @return mixed
     */
    public function find($searchCriteria = false)
    {
        $result = array();

        if (is_int($searchCriteria)) {
            $primaryKeyValueForSearch = intval($searchCriteria);
            $result = $this->_db_adapter->getOneResult($this->_table_name, array($this->_primary_key => $primaryKeyValueForSearch));
        } else if (is_array($searchCriteria) && !empty($searchCriteria)) {
            $result = $this->_db_adapter->Select($this->_table_name, $searchCriteria);
        }

        return $this->convertSearchResultIntoBean($result);
    }

    /**
     * @param array $resultArray
     * @return $this
     * @throws Exception
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
}