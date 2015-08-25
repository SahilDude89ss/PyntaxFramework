<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 25/08/15
 * Time: 2:20 PM
 */

namespace Pyntax\Html\Element;


interface ElementFactoryInterface
{
    public function generateElement($elTag, $elData, $replaceNonStringCharacters = false);
}