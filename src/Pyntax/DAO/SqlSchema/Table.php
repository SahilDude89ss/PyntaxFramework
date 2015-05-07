<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 7/05/15
 * Time: 11:08 AM
 */

namespace Pyntax\DAO\SqlSchema;

use Aura\SqlSchema\ColumnFactory;
use Aura\SqlSchema\MysqlSchema;
use \PDO;
use Pyntax\DAO\Sql\QueryBuilder;

/**
 * Class Table
 * @package Pyntax\DAO\SqlSchema
 */
class Table {

    protected $name = false;

    protected $dbConnection = false;

    protected $queryBuilder = false;

    protected $primary_key = false;

    protected $foreign_keys = array();

    protected $columnFactory = false;

    protected $mysqlSchema = false;

    protected $schema = false;

    protected $_columns = array();

    public function __construct($name, PDO $dbConnection, QueryBuilder $queryBuilder) {

        $this->name = $name;

        $this->dbConnection = $dbConnection;

        $this->queryBuilder = $queryBuilder;

        $this->columnFactory = new ColumnFactory();

        $this->mysqlSchema = new MysqlSchema($this->dbConnection, $this->columnFactory);

        $this->setColumnsWithRelationships();

        $this->populateColumns();
    }

    protected function populateColumns() {
        $this->_columns = $this->mysqlSchema->fetchTableCols($this->getTableName());
    }

    protected function setColumnsWithRelationships()  {
        $result = $this->dbConnection->query($this->queryBuilder->getForeignKeys($this->getTableName()));
        $columnsWithKeys = $result->fetchAll(PDO::FETCH_ASSOC);

        foreach($columnsWithKeys as $index => $key) {
            if($key['CONSTRAINT_NAME'] == 'PRIMARY') {
                $this->primary_key = $key['COLUMN_NAME'];
            } else {
                $this->foreign_keys[] = array(
                    'table' => $key['REFERENCED_TABLE_NAME'],
                    'column' => $key['REFERENCED_COLUMN_NAME']
                );
            }
        }
    }

    public function getTableName() {
        return $this->name;
    }

    public function getPrimaryKey() {
        return $this->primary_key;
    }

    public function getForeignKeys() {
        return $this->foreign_keys;
    }
}