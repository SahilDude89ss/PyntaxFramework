<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2015 Sahil Sharma (SahilDude89ss@gmail.com)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Pyntax\Html\Table;

use Pyntax\DAO\Bean\BeanInterface;

/**
 * Class TableFactory
 * @package Pyntax\Html\Table
 */
class TableFactory extends TableFactoryAbstract
{
    /**
     * @param BeanInterface $bean
     * @param string $findCondition
     * @param bool|false $returnString
     * @return string
     */
    public function generateTable(BeanInterface $bean, $findCondition = "", $returnString = false)
    {
        $tableHtml = "";

        $result = $bean->find($findCondition, true);

        if (!empty($result)) {
            $tableHtml = $this->generateTableHtml($bean, $result);
        }

        if ($returnString) {
            return $tableHtml;
        }

        echo $tableHtml;
    }

}