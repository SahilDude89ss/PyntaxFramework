<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 7/05/15
 * Time: 3:21 PM
 */

namespace Pyntax\DAO\Bean;
use Pyntax\DAO\SqlSchema\Table;

class Bean {

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

    public function get($id , $loadRelatedData = true) {
        $this->_data = $this->table->get($id, $loadRelatedData);
        return $this;
    }

    public function select($where = array(), $loadRelatedData = true, $limit = 10) {
        $this->_data = $this->table->select(null, $where, $loadRelatedData, $limit);

        if(count($this->_data) > 1) {
            $r = array();
            foreach($this->_data as $beanData) {
                $r[] = new Bean($this->table, $beanData);
            }

            return $r;
        } else if(count($this->_data) == 1) {
            $this->_data = $this->_data[0];
        }

        return $this;
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