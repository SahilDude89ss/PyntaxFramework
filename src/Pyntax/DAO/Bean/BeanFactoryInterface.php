<?php
/**
 * Created by PhpStorm.
 * User: sahil
 * Date: 8/05/15
 * Time: 11:02 PM
 */

namespace Pyntax\DAO\Bean;

interface BeanFactoryInterface {

    public function getBean($tableName);

}