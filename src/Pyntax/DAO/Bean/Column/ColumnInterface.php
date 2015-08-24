<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 29/05/15
 * Time: 2:04 PM
 */

namespace Pyntax\DAO\Bean\Column;


interface ColumnInterface {

    public function getType();

    public function getSize();

    public function getDroDownValues();

    public function isNullNotAllowed();

    public function isEmptyAllowed();

    public function isPrimaryKey();

    public function validate();

    public function isColumnVisible();

    public function getName();
}