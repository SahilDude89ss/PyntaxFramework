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

    protected $columns = array();

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
        $this->columns = $this->mysqlSchema->fetchTableCols($this->getTableName());
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

    /**
     * @param $id
     * @param bool $loadRelatedData
     *
     * @return array
     */
    public function get($id, $loadRelatedData = false) {
        $result = $this->dbConnection->query($this->queryBuilder->selectById($this, $id, $loadRelatedData));
        $columns = $result->fetchAll(PDO::FETCH_ASSOC);

        if($columns > 0) {
            return $columns[0];
        }

        return $this->getSelectColumns();
    }

    public function hasColumn($name) {
        return array_key_exists($this->columns,$name);
    }

    public function getSelectColumns() {
        return array_keys($this->columns);
    }

    public function getColumns() {
        return $this->columns;
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