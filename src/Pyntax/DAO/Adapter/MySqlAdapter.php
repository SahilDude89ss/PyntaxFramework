<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2015 Sahil Sharma (SahilDude89ss@gmail.com)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Pyntax\DAO\Adapter;

use Aura\SqlQuery\QueryFactory;
use Pyntax\Config\Config;

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
        if (preg_match("/.*(SELECT|INSERT|UPDATE|SHOW|DELETE).*/", $sql)) {

            $result = false;

            $this->sql = $sql;

            //Always prepare the SQL statement first
            $query = $this->connection->prepare($sql);

            if (preg_match('/\s*:(.+?)\s/', $sql)) {
                if (is_array($bindingValues) && !empty($bindingValues)) {
                    foreach ($bindingValues as $key => $val) {
                        //Clean the bindingValues. Check if they start with :
                        if (!preg_match('/:.*/', $key)) {
                            $bindingValues[":" . $key] = $val;
                            unset($bindingValues[$key]);
                        }
                    }
                }


                //Add the binding variables to the query
                if ($query->execute($bindingValues)) {
                    return $this->getLastInsertID();
                } else {
                    //@ToDo:When the save or update fails make sure we handle the error gracefully!
                    throw new \Exception(implode("\n", $query->errorInfo()));
                }

            } else {
                $result = $query->execute();
            }

            if (preg_match('/.*(SELECT|SHOW).*/', $sql)) {
                $query->setFetchMode(\PDO::FETCH_ASSOC);
                return $query->fetchAll();
            }

            return $result;
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
            'Indexes' => $this->ShowIndexes($tableName),
            'ForeignKeys' => $this->getForeignKeys($tableName)
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
    public function Select($table, $where = null, $limit = null , $groupBy = null, $orderBy = null)
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

        if (!empty($limit)) {
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
    protected function convertWhereToString(array $whereArray = array(), $concatenationOperator = " AND ", $assignmentOperator = "=")
    {
        if (empty($whereArray)) {
            return false;
        }

        $returnWhereStringToken = array();

        //Check if the the keys is AND or OR, if yes then use that as the concat operator
        foreach ($whereArray as $key => $val) {
            $_key = strtoupper($key);

            if ((in_array($_key, array("OR","AND","LIKE"))) && is_array($val) && !empty($val)) {
                $_assignmentOperator = ($_key == "LIKE") ? $_key : $assignmentOperator;
                $returnWhereStringToken = array_merge($returnWhereStringToken, array($this->convertWhereToString($val, " " . trim($key) . " ", $_assignmentOperator)));
            } else {
                $returnWhereStringToken[] = "$key {$assignmentOperator} '$val'";
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

            if (is_string($where)) {
                $update->where($where);
            } else if (is_array($where) && !empty($where)) {
                $update->where($this->convertWhereToString($where));
            }

            //return the insert id
            return $this->exec($update->getStatement(), $update->getBindValues());

        }

        return false;
    }

    /**
     * DELETE [LOW_PRIORITY] [QUICK] [IGNORE] FROM tbl_name
     *  [WHERE where_condition]
     *  [ORDER BY ...]
     *  [LIMIT row_count]
     *
     * @param $table
     * @param null $where
     *
     * @return bool
     */
    public function Delete($table, $where = null)
    {
        if (empty($table) || empty($where) && is_array($where)) {
            return false;
        }

        $delete = $this->queryFactory->newDelete();

        //Set the table from which the data is to be deleted
        $delete->from($table);

        //Set the where
        $delete->where($this->convertWhereToString($where));

        return $this->exec($delete->getStatement());
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

        //Before we set the columns, make sure we only use the column that re meant to be filled.
        foreach ($data as $key => $val) {
            if (empty($val)) {
                unset($data[$key]);
            }
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

    /**
     * @param $table
     * @return bool|mixed
     */
    public function getForeignKeys($table)
    {
        $config = Config::read('database');

        if(isset($config['database'])) {
            $database = $config['database'];

            $SQL = "SELECT CONSTRAINT_NAME, TABLE_SCHEMA, TABLE_NAME, COLUMN_NAME,
                      REFERENCED_TABLE_SCHEMA, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
                        FROM information_schema.KEY_COLUMN_USAGE
                          WHERE information_schema.KEY_COLUMN_USAGE.TABLE_NAME = '{$table}'
                          AND information_schema.KEY_COLUMN_USAGE.TABLE_SCHEMA = '{$database}'
                          AND (information_schema.KEY_COLUMN_USAGE.REFERENCED_TABLE_SCHEMA = '{$database}'
                          AND information_schema.KEY_COLUMN_USAGE.REFERENCED_TABLE_NAME IS NOT NULL
                          AND information_schema.KEY_COLUMN_USAGE.REFERENCED_COLUMN_NAME IS NOT NULL)";

            return $this->exec($SQL);
        }

        return false;
    }
}