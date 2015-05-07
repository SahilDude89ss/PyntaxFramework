<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 7/05/15
 * Time: 2:19 PM
 */

namespace Pyntax\DAO\Sql;
use Aura\SqlQuery\QueryFactory;

class QueryBuilder {

    protected $queryFactory = null;

    /**
     * @param string $dbType
     */
    public function __construct($dbType = 'mysql') {
        $this->queryFactory = new QueryFactory($dbType);
    }

    /**
     * @param $tableName
     * @return string
     */
    public function getForeignKeys($tableName) {
        $select = $this->queryFactory->newSelect();
        $select->cols(array('TABLE_NAME','COLUMN_NAME','CONSTRAINT_NAME','REFERENCED_TABLE_NAME','REFERENCED_COLUMN_NAME'))
            ->from('INFORMATION_SCHEMA.KEY_COLUMN_USAGE')
            ->where("TABLE_NAME = '$tableName';");

        return $select->__toString();
    }
}