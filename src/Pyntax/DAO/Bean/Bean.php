<?php

namespace Pyntax\DAO\Bean;

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
     * @param bool $tableName
     * @param AdapterInterface $adapter
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
        // TODO: Implement save() method.
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }

    /**
     * This function is used to find data from the database.
     *
     * @param $searchCriteria
     */
    public function find($searchCriteria = false)
    {
        if(is_int($searchCriteria)) {
            $primaryKeyValueForSearch  = intval($searchCriteria);
        }

        var_dump($this->_db_adapter->Select($this->_table_name)); die;
    }
}