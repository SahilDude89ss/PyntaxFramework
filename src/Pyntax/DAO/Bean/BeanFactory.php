<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 7/05/15
 * Time: 3:30 PM
 */

namespace Pyntax\DAO\Bean;
use Pyntax\DAO\SqlSchema\Table;
use Pyntax\DAO\Sql\QueryBuilder;
use PDO;

class BeanFactory {

    protected $dbConnection = false;

    protected  $queryBuilder = false;

    public function __construct(PDO $dbConnection, QueryBuilder $queryBuilder) {
        $this->dbConnection = $dbConnection;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @param $tableName
     * @return Bean
     */
    public function createNewBean($tableName) {
        return new Bean(new Table($tableName,$this->dbConnection,$this->queryBuilder));
    }
}