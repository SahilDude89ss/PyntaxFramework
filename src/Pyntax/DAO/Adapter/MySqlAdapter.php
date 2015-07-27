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

    /**
     * @var QueryFactory|null
     */
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
        if (preg_match("/.*(SELECT|INSERT|UPDATE|SHOW).*/", $sql)) {

            $this->sql = $sql;

            //Always prepare the SQL statement first
            $query = $this->connection->prepare($sql);

            if (preg_match('/\s*:(.+?)\s/', $sql)) {
                //Add the binding variables to the query
                if($query->execute($bindingValues)) {
                    return $this->getLastInsertID();
                }

            } else {
                $query->execute();
            }

            if (preg_match('/.*(SELECT|SHOW).*/', $sql)) {
                $query->setFetchMode(\PDO::FETCH_ASSOC);
            }

            return $query->fetchAll();
        }


        return false;
    }

    /**
     * @param $tableName
     * @return array
     */
    public function getMetaData($tableName)
    {
        return array(
            'Fields' => $this->ShowColumns($tableName),
            'Indexes' => $this->ShowIndexes($tableName)
        );
    }

    /**
     * @param $tableName
     * @return mixed
     */
    public function ShowIndexes($tableName)
    {
        return $this->exec("SHOW INDEXES FROM {$tableName}");
    }

    /**
     * @param $tableName
     * @return mixed
     */
    public function ShowColumns($tableName)
    {
        return $this->exec("SHOW COLUMNS FROM {$tableName}");
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
     * @param null $where
     * @param null $groupBy
     * @param null $orderBy
     * @param int $limit
     *
     * @return mixed
     */
    public function Select($table, $where = null, $groupBy = null, $orderBy = null, $limit = 0)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(array('*'))
            ->from($table);

        if (is_string($where) && strlen($where) > 0) {
            $select->where($where);
        } else if (is_array($where) && count($where) > 0) {
            $select->where($this->convertWhereToString($where));
        }

        if (is_array($groupBy)) {
            $select->groupBy($groupBy);
        }

        if (is_array($orderBy)) {
            $select->orderBy($orderBy);
        }

        if ($limit > 0) {
            $select->limit($limit);
        }
        return $this->exec($select->getStatement(), $where);
    }

    /**
     * This function converts an array into a Where String.
     *
     * @param array $whereArray
     * @param string $concatenationOperator
     *
     * @return bool|string
     */
    protected function convertWhereToString(array $whereArray = array(), $concatenationOperator = " AND ")
    {
        if (empty($whereArray)) {
            return false;
        }

        $returnWhereStringToken = array();

        //Check if the the keys is AND or OR, if yes then use that as the concat operator
        foreach ($whereArray as $key => $val) {
            if ((strtoupper($key) == 'OR' || strtoupper($key) == 'AND') && is_array($val) && !empty($val)) {
                $returnWhereStringToken = array_merge($returnWhereStringToken, array($this->convertWhereToString($val, " " . trim($key) . " ")));
            } else {
                $returnWhereStringToken[] = "$key = '$val'";
            }
        }

        return implode($concatenationOperator, $returnWhereStringToken);
    }

    /**
     * Executes the current SQL query and returns the first result as ASSOC result set
     *
     * @param $table
     * @param null $where
     * @param null $groupBy
     * @param null $orderBy
     *
     * @return mixed
     */
    public function getOneResult($table, $where = null, $groupBy = null, $orderBy = null)
    {
        return $this->Select($table, $where, $groupBy, $orderBy, 1);
    }

    /**
     * @ToDo: Implement the Where clause
     *
     * Updates the present data in the table.
     *
     * UPDATE [LOW_PRIORITY] [IGNORE] table_reference
     *  SET col_name1={expr1|DEFAULT} [, col_name2={expr2|DEFAULT}] ...
     *  [WHERE where_condition]
     *  [ORDER BY ...]
     *  [LIMIT row_count]
     *
     * @param $table
     * @param array $data
     * @param null $where
     *
     * @return bool|mixed
     */
    public function Update($table, $data = array(), $where = null)
    {
        if (empty($table) || empty($data)) {
            return false;
        }

        //Get all the columns that are being set
        $columns = array_keys($data);

        //If the columns are not empty, proceed with the save
        if (!empty($columns)) {
            //Create a newInsert object
            $update = $this->queryFactory->newUpdate();

            //Set the table
            $update->table($table);

            //Set the columns
            $update->cols($columns);

            //Bind the values to be saved
            $update->bindValues($data);

            //return the insert id
            return $this->exec($update->getStatement(), $update->getBindValues());

        }

        return false;
    }

    /**
     * Saves the data in the table with a new id;
     *
     * INSERT INTO tbl_temp2 (fld_id)
     *  SELECT tbl_temp1.fld_order_id
     *  FROM tbl_temp1 WHERE tbl_temp1.fld_order_id > 100;
     *
     * @param $table
     * @param array $data
     *
     * @return mixed
     */
    public function Insert($table, $data = array())
    {
        if (empty($table) || empty($data)) {
            return false;
        }

        //Get all the columns that are being set
        $columns = array_keys($data);

        //If the columns are not empty, proceed with the save
        if (!empty($columns)) {
            //Create a newInsert object
            $insert = $this->queryFactory->newInsert();

            //Set the table
            $insert->into($table);

            //Set the columns
            $insert->cols($columns);

            //Bind the values to be saved
            $insert->bindValues($data);

            //return the insert id
            return $this->exec($insert->getStatement(), $insert->getBindValues());

        }

        return false;
    }

    /**
     * Returns the last insert ID
     *
     * @return string|int
     */
    public function getLastInsertID()
    {
        $result = $this->exec('SELECT LAST_INSERT_ID() as `id`');
        return (isset($result[0]['id'])) ? $result[0]['id'] : false;
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