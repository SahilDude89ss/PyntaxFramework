<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 8/05/15
 * Time: 10:57 AM
 */

namespace OLDPyntaxDAO\Bean;


use OLDPyntaxCommon\BeanInterface;

abstract class BeanAbstract implements  BeanInterface {

    protected $table = false;

    protected $_data = array();

    protected $_column_definition = array();

    public function __construct(Table $table, array $data = array()) {
        $this->table = $table;
        $this->_column_definition = $table->getColumns();

        $this->setBeanData($data);
    }

    protected function setBeanData(array $data) {
        $this->_data = $data;
    }

    function __get($name)
    {
        if(isset($this->_data[$name])) {
            return $this->_data[$name];
        }

        return false;
    }

    function __set($name, $value)
    {
        if(isset($this->_data[$name])) {
            $this->_data[$name] = $value;

            return true;
        }

        return false;
    }

    public function getForeignKeys() {
        return $this->table->getForeignKeys();
    }

    public function getData() {
        return $this->_data;
    }
}