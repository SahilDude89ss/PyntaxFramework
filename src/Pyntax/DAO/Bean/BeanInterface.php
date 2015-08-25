<?php
/**
 * Created by PhpStorm.
 * User: sahil
 * Date: 8/05/15
 * Time: 11:01 PM
 */

namespace Pyntax\DAO\Bean;

interface BeanInterface {

    public function save();

    public function delete();

    public function find($searchCriteria = false, $returnArray = false);

    public function getDisplayColumns();
}