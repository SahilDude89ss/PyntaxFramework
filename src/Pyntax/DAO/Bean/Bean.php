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

    public function __construct(Table $table) {
        $this->table = $table;
        $this->_column_definition = $table->getColumns();
    }

    public function get($id , $loadRelatedData = false) {
        $this->_data = $this->table->get($id, $loadRelatedData);
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

}