<?php

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

    /**
     * Saves the data in the table with a new id;
     *
     * @param $table
     * @param array $data
     *
     * @return mixed
     */
    public function Insert($table, $data = array());

    /**
     * Updates the present data in the table.
     *
     * @param $table
     * @param array $data
     * @param null $where
     *
     * @return mixed
     */
    public function Update($table, $data = array(), $where = null);

    /**
     * Deletes the present data from the table
     *
     * @param $table
     * @param null $where
     *
     * @return mixed
     */
    public function Delete($table, $where = null);
}