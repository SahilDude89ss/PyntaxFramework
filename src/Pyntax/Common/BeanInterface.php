<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 8/05/15
 * Time: 10:53 AM
 */

namespace OLDPyntaxCommon;


interface BeanInterface {

    public function selectById($id = array(), $loadRelatedData = true);

    public function selectOne($where = array(), $loadRelatedData = true);

    public function select($where = array(), $loadRelatedData = true, $limit = 10);

    public function insert($data = array());

    public function updated($data = array(),array $where = array());

    public function delete($where = array());

}