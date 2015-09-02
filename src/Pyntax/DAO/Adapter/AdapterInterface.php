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
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Pyntax\DAO\Adapter;

/**
 * Interface AdapterInterface
 * @package Pyntax\DAO\Adapter
 */
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
     * @param $table
     * @return mixed
     */
    public function getForeignKeys($table);

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