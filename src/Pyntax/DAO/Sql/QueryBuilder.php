<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 7/05/15
 * Time: 2:19 PM
 */

namespace Pyntax\DAO\Sql;
use Aura\SqlQuery\QueryFactory;
use Pyntax\DAO\SqlSchema\Table;

class QueryBuilder {

    protected $queryFactory = null;

    /**
     * @param string $dbType
     */
    public function __construct($dbType = 'mysql') {
        $this->queryFactory = new QueryFactory($dbType);
    }

    public function getForeignKeys($tableName) {
        $select = $this->queryFactory->newSelect();
        $select->cols(array('TABLE_NAME','COLUMN_NAME','CONSTRAINT_NAME','REFERENCED_TABLE_NAME','REFERENCED_COLUMN_NAME'))
            ->from('INFORMATION_SCHEMA.KEY_COLUMN_USAGE')
            ->where("TABLE_NAME = '$tableName';");

        return $select->__toString();
    }

    public function selectById(Table $table, $id) {
        $select = $this->queryFactory->newSelect();
        $select->cols($table->getSelectColumns())
            ->from($table->getTableName())
            ->where("{$table->getPrimaryKey()} = $id");

        return $select->__toString()." LIMIT 1";
    }

    public function select(Table $table, $where, $limit = 10) {
        $select = $this->queryFactory->newSelect();
        $select->cols($table->getSelectColumns())
            ->from($table->getTableName());

        if(strlen($where) > 0)  {
            $select->where($where);
        }

        return $select->__toString().(($limit) ? " LIMIT $limit" : "");
    }
}