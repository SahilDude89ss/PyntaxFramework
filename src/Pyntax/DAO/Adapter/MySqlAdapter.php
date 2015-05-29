<?php

namespace Pyntax\DAO\Adapter;

use Aura\SqlQuery\QueryFactory;
use Pyntax\Common\AdapterInterface;

/**
 * Class MySqlAdapter
 * @package Pyntax\DAO\Adapter
 */
class MySqlAdapter implements AdapterInterface
{

    /**
     * database stores the name of the current database.
     * @var string
     */
    protected $database = "";

    /**
     * Sql stores the current executing SQL.
     * @var string
     */
    protected $sql = "";

    /**
     * PDO stores the PDO connection
     * @var \PDO
     */
    protected $connection = NULL;

    protected $queryFactory = NULL;

    /**
     * MySqlAdapter can only be instantiated with a PDO connection object.
     * @param \PDO $connection
     */
    public function __construct(\PDO $connection)
    {
        $this->queryFactory = new QueryFactory('mysql');
        $this->connection = $connection;
    }

    /**
     * Returns the current/last executed query
     *
     * @return string
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * @return string
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Executes the current SQL query.
     * Returns a ASSOC result set.
     *
     * @param string $sql
     * @param array $bindingValues
     * @param bool $insert
     * @return mixed
     */
    public function exec($sql = "", $bindingValues = array(), $insert = true)
    {
        //Check if the query is a SELECT|INSERT|UPDATE and :variation is available
        if (preg_match('/.*(SELECT|INSERT|UPDATE).*/', $sql) && preg_match('\s*:(.+?)\s', $sql)) {

            $this->sql = $sql;

            //Always prepare the SQL statement first
            $query = $this->connection->prepare($sql);

            //Add the binding variables to the query
            $query->execute($bindingValues);

            if (preg_match('/.*(SELECT).*/', $sql)) {
                $query->setFetchMode(\PDO::FETCH_ASSOC);
            }

            return $query->fetch();
        }

    }

    /**
     * SELECT
     * [ALL | DISTINCT | DISTINCTROW ]
     * [HIGH_PRIORITY]
     * [STRAIGHT_JOIN]
     * [SQL_SMALL_RESULT] [SQL_BIG_RESULT] [SQL_BUFFER_RESULT]
     * [SQL_CACHE | SQL_NO_CACHE] [SQL_CALC_FOUND_ROWS]
     * select_expr [, select_expr ...]
     * [FROM table_references
     * [WHERE where_condition]
     * [GROUP BY {col_name | expr | position}
     * [ASC | DESC], ... [WITH ROLLUP]]
     * [HAVING where_condition]
     * [ORDER BY {col_name | expr | position}
     * [ASC | DESC], ...]
     * [LIMIT {[offset,] row_count | row_count OFFSET offset}]
     *
     * @param $table
     * @param String $where
     * @param null $groupBy
     * @param null $orderBy
     * @param int $limit
     */
    public function Select($table, $where = null,  $groupBy = null, $orderBy = null, $limit = 0) {
        $select = $this->queryFactory->newSelect();
        $select->cols('*')
            ->from($table);

        if(is_string($where) && strlen($where) > 0) {
            $select->where($where);
        }

        if(is_array($groupBy)) {
            $select->groupBy($groupBy);
        }

        if(is_array($orderBy)) {
            $select->orderBy($orderBy);
        }

        if($limit > 0) {
            $select->limit($limit);
        }
    }

    /**
     * Executes the current SQL query and returns the first result as ASSOC result set
     *
     * @param string $sql
     * @param array $bindingValues
     * @return mixed
     */
    public function getOneResult($sql = "", $bindingValues = array())
    {
        // TODO: Implement getOneResult()
    }

    /**
     * Returns the last insert ID
     *
     * @return string|int
     */
    public function getLastInsertID()
    {
        // TODO: Implement getLastInsertID() method.
    }

    /**
     * Begin a transaction
     *
     * @return mixed
     */
    public function begin()
    {
        // TODO: Implement begin() method.
    }

    /**
     * Commits a transaction
     *
     * @return mixed
     */
    public function commit()
    {
        // TODO: Implement commit() method.
    }

    /**
     * Rollbacks a transaction.
     *
     * @return mixed
     */
    public function rollback()
    {
        // TODO: Implement rollback() method.
    }

    /**
     * Sets the cache on/off. As a result any query that is executed is first searched in the CACHING engine for a
     * quick result.
     *
     * @param bool $yesNo
     * @return mixed
     */
    public function setCacheFacade($yesNo = false)
    {
        // TODO: Implement setCacheFacade() method.
    }
}