<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 29/05/15
 * Time: 2:03 PM
 */

namespace Pyntax\DAO\Bean\Column;


class Column implements ColumnInterface {

    protected $_definition = array();

    public function __construct(array $columnDefinitions = null) {

    }

    public function getType()
    {
        // TODO: Implement getType() method.
    }

    public function getSize()
    {
        // TODO: Implement getSize() method.
    }

    public function getDroDownValues()
    {
        // TODO: Implement getDroDownValues() method.
    }

    public function isNullAllowed()
    {
        // TODO: Implement isNullAllowed() method.
    }

    public function isEmptyAllowed()
    {
        // TODO: Implement isEmptyAllowed() method.
    }
}