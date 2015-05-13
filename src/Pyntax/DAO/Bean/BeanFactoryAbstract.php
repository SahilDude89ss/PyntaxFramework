<?php
/**
 * Created by PhpStorm.
 * User: sahil
 * Date: 8/05/15
 * Time: 11:08 PM
 */

namespace Pyntax\DAO\Bean;


use Pyntax\Common\BeanFactoryInterface;

abstract class BeanFactoryAbstract implements BeanFactoryInterface {

    protected $dbConnection = null;

    public function getDbConnection() {
        return $this->dbConnection;
    }
}