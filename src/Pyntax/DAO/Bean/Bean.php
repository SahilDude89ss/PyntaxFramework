<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 7/05/15
 * Time: 3:21 PM
 */

namespace OLDPyntaxDAO\Bean;
use OLDPyntaxDAO\SqlSchema\Table;

class Bean extends BeanAbstract {

    public function selectOne($where = array(), $loadRelatedData = true)
    {
        return $this->select($where,$loadRelatedData,1);
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