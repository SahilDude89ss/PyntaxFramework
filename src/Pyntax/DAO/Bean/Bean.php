<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 7/05/15
 * Time: 3:21 PM
 */

namespace Pyntax\DAO\Bean;
use Pyntax\DAO\SqlSchema\Table;

class Bean extends BeanAbstract {

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

    public function selectOne($where = array(), $loadRelatedData = true)
    {
        // TODO: Implement selectOne() method.
    }

    public function select($where = array(), $loadRelatedData = true, $limit = 10)
    {
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

    public function insert($data = array())
    {
        // TODO: Implement insert() method.
    }

    public function updated($data = array(), array $where = array())
    {
        // TODO: Implement updated() method.
    }

    public function delete($where = array())
    {
        // TODO: Implement delete() method.
    }

    public function selectById($id = array(), $loadRelatedData = true)
    {
        // TODO: Implement selectById() method.
    }
}