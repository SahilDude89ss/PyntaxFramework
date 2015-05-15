<?php
/**
 * Created by PhpStorm.
 * User: sahil
 * Date: 14/05/15
 * Time: 10:22 PM
 */

namespace Pyntax\DAO\Adapter;


use Pyntax\Common\AdapterInterface;

class MySqlAdapter implements AdapterInterface {

    /**
     * Returns the current/last executed query
     *
     * @return string
     */
    public function getSql()
    {
        // TODO: Implement getSql() method.
    }

    /**
     * @return string
     */
    public function getDatabase()
    {
        // TODO: Implement getDatabase() method.
    }

    /**
     * Executes the current SQL query.
     * Returns a ASSOC result set.
     *
     * @param string $sql
     * @param array $bindingValues
     * @return mixed
     */
    public function exec($sql = "", $bindingValues = array())
    {
        // TODO: Implement exec() method.
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
        // TODO: Implement getOneResult() method.
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