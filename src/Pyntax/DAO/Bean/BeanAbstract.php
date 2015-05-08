<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 8/05/15
 * Time: 10:57 AM
 */

namespace Pyntax\DAO\Bean;


use Pyntax\Common\BeanInterface;

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
}