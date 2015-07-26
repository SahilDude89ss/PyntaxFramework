<?php
/**
 * Created by PhpStorm.
 * User: sahil
 * Date: 14/05/15
 * Time: 9:47 PM
 */

namespace Pyntax\Common;


interface AdapterInterface {

    /**
     * Returns the current/last executed query
     *
     * @return string
     */
    public function getSql();

    /**
     * @return string
     */
    public function getDatabase();

    /**
     * Executes the current SQL query.
     * Returns a ASSOC result set.
     *
     * @param string $sql
     * @param array $bindingValues
     * @return mixed
     */
    public function exec($sql = "", $bindingValues = array());

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
    public function getOneResult($table, $where = null,  $groupBy = null, $orderBy = null);

    /**
     * Returns the last insert ID
     *
     * @return string|int
     */
    public function getLastInsertID();

    /**
     * Begin a transaction
     *
     * @return mixed
     */
    public function begin();

    /**
     * Commits a transaction
     *
     * @return mixed
     */
    public function commit();

    /**
     * Rollbacks a transaction.
     *
     * @return mixed
     */
    public function rollback();

    /**
     * Sets the cache on/off. As a result any query that is executed is first searched in the CACHING engine for a
     * quick result.
     *
     * @param bool $yesNo
     * @return mixed
     */
    public function setCacheFacade($yesNo = false);

    /**
     * Returns the columns in a table and indexes.
     *
     * @param $tableName
     * @return mixed
     */
    public function getMetaData($tableName);
}