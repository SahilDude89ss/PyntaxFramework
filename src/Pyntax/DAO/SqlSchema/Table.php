<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 7/05/15
 * Time: 11:08 AM
 */

namespace OLDPyntaxDAO\SqlSchema;

use Aura\SqlQuery\Exception;
use Aura\SqlSchema\ColumnFactory;
use Aura\SqlSchema\MysqlSchema;
use \PDO;
use OLDPyntaxDAO\Sql\QueryBuilder;

/**
 * Class Table
 * @package OLDPyntaxDAO\SqlSchema
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
                    'f.table' => $key['REFERENCED_TABLE_NAME'],
                    'f.column' => $key['REFERENCED_COLUMN_NAME'],
                    'this.column' => $key['COLUMN_NAME']
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
    public function get($id, $loadRelatedData = true) {
        $result = $this->dbConnection->query($this->queryBuilder->selectById($this, $id, $loadRelatedData));
        $columns = $result->fetchAll(PDO::FETCH_ASSOC);

        if($loadRelatedData) {
            if(count($columns) > 0) {
                $columns = $columns[0];

                foreach($this->getForeignKeys() as $foreignKey) {
                    $columns[$foreignKey['table']] = $this->select(
                        new Table('Country', $this->dbConnection, $this->queryBuilder),
                        array(
                            'Code' => $foreignKey['column']
                        )
                    );
                }
            }
        }

        return $columns;
    }

    /**
     * @param Table $table
     * @param array $where
     * @param bool $loadRelatedData
     * @param int $limit
     *
     * @return array
     */
    public function select(Table $table = null, array $where, $loadRelatedData = true, $limit = 10) {
        $table = is_null($table) ? $this : $table;
        $result = $this->dbConnection->query($this->queryBuilder->select($table, $this->computeWhere($where), $limit));
        $columns = $result->fetchAll(PDO::FETCH_ASSOC);

        if($loadRelatedData)
        {
            if(count($columns) > 0)
            {
                foreach($columns as &$columnData)
                {
                    foreach($table->getForeignKeys() as $foreignKey)
                    {
                        if(!empty($columnData[$foreignKey['this.column']])) {
                            try {
                                $columnData[$foreignKey['f.table']] = $this->select(
                                    new Table($foreignKey['f.table'], $this->dbConnection, $this->queryBuilder),
                                    array($foreignKey['f.column'] => $columnData[$foreignKey['this.column']]),
                                    true,
                                    false
                                );
                            } catch(Exception $e) {
                                echo $e->getMessage(); die;
                            }
                        }
                    }
                }
            }
        }

        return $columns;
    }

    /**
     * @param array $where
     * @return string
     */
    protected function computeWhere(array $where) {
        return implode(', ', array_map(function ($v, $k) {
            return sprintf("%s='%s'", $k, $v);
        }, $where, array_keys($where)));
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