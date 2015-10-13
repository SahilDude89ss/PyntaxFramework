<?php
/**
 * Created by PhpStorm.
 * User: sahil
 * Date: 29/09/15
 * Time: 9:34 PM
 */

namespace Pyntax\DAO\Bean;

use Pyntax\Cache\CacheFactory;
use Pyntax\Config\Config;
use Pyntax\Config\ConfigAwareInterface;
use Pyntax\DAO\Bean\Column\Column;
use Pyntax\DAO\Adapter\AdapterInterface;

abstract class BeanAbstract implements BeanInterface, ConfigAwareInterface
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
     * @var array
     */
    protected $_foreign_keys = array();

    /**
     * @var bool
     */
    protected $_cache_factory = false;

    /**
     * @var array
     */
    protected $_bean_config = array();

    /**
     * @var int
     */
    protected $_depth_counter = 0;

    /**
     * @param bool|false $tableName
     * @param AdapterInterface|null $adapter
     */
    public function __construct($tableName = false, AdapterInterface $adapter = null)
    {
        if (!$tableName || is_null($adapter)) {
            return false;
        }

        $this->_cache_factory = new CacheFactory();

        $this->_db_adapter = $adapter;
        $this->_table_name = $tableName;

        $this->loadMetaData();
        $this->loadConfig();
    }

    public function setDepthCounter($val) {
        $this->_depth_counter = $val;
    }

    protected function loadConfig()
    {
//        $_config = Config::readConfig('orm');
        $_config = new Config('orm', 'orm.config.php');
        $this->_bean_config = isset($_config) ? $_config : array();
    }

    /**
     * @param array $config
     * @param $elementName
     * @param $beanName
     * @param string $customKeyName
     *
     * @return mixed
     */
    public function getConfigForElement(array $config, $elementName, $beanName, $customKeyName = 'beans')
    {
        if(!isset($config[$elementName])) {
            return false;
        }

        $_custom_config = isset($config[$customKeyName][$beanName][$elementName]) ? $config[$customKeyName][$beanName][$elementName] : false;

        if(is_array($_custom_config)) {
            return array_merge($config[$elementName], $_custom_config);
        } else if(!empty($_custom_config)) {
            return $_custom_config;
        }

        return $config[$elementName];
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

        if (isset($metaData['ForeignKeys'])) {
            $this->setForeignKeyDefinitions($metaData['ForeignKeys']);
        }
    }

    private function setForeignKeyDefinitions($foreignKeys)
    {
        foreach ($foreignKeys as $_foreign_key) {

            if (isset($_foreign_key['REFERENCED_COLUMN_NAME']) && isset($_foreign_key['REFERENCED_TABLE_NAME'])
                && isset($_foreign_key['COLUMN_NAME'])
            ) {
                $this->_foreign_keys[] = array(
                    'column_name' => $_foreign_key['COLUMN_NAME'],
                    'foreign_key_table' => $_foreign_key['REFERENCED_TABLE_NAME'],
                    'foreign_key_column' => $_foreign_key['REFERENCED_COLUMN_NAME']
                );
            }
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
        $_meta_data = $this->loadMetaDataFromCache($this->_table_name);

        if (empty($_meta_data)) {
            $_meta_data = $this->_db_adapter->getMetaData($this->_table_name);
            $this->_cache_factory->write($this->_table_name, $_meta_data);
        }

        $this->processMetaData($_meta_data);
    }

    /**
     * @param $beanName
     * @return bool|mixed
     */
    private function loadMetaDataFromCache($beanName)
    {
        if (!empty($this->_cache_factory)) {
            $_meta_data_form_cache = $this->_cache_factory->read($beanName);

            if (!empty($_meta_data_form_cache)) {
                return unserialize($_meta_data_form_cache);
            }
        }

        return false;
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

                $this->loadRelatedBeanData();
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
    protected function validateColumns()
    {
        return true;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __get($name)
    {
        return (isset($this->_columns[$name]) ? $this->_columns[$name] : false);
    }

    /**
     * @param $name
     * @param $value
     *
     * @return bool
     */
    public function __set($name, $value)
    {
        if (isset($this->_columns, $name)) {
            $this->_columns[$name] = $value;
        }
    }
}