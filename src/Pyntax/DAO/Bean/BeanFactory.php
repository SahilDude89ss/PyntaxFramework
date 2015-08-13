<?php

namespace Pyntax\DAO\Bean;
use Pyntax\DAO\Adapter\AdapterInterface;

/**
 * Class BeanFactory
 * @package Pyntax\DAO\Bean
 */
class BeanFactory implements BeanFactoryInterface {

    /**
     * @var null|AdapterInterface
     */
    protected $_db_adapter = null;

    /**
     * @param AdapterInterface $dbAdapter
     */
    public function __construct(AdapterInterface $dbAdapter = null) {
        if(is_null($dbAdapter)) {
            return false;
        }

        $this->_db_adapter = $dbAdapter;
    }

    /**
     * @param $tableName
     * @return Bean
     */
    public function getBean($tableName)
    {
        return new Bean($tableName, $this->_db_adapter);
    }
}