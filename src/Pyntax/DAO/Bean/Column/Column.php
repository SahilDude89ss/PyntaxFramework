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
 * FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Pyntax\DAO\Bean\Column;

/**
 * Class Column
 * @package Pyntax\DAO\Bean\Column
 */
class Column implements ColumnInterface
{
    /**
     * @var array
     */
    protected $definition = array();

    /**
     * Stores the name of the variable
     *
     * @var bool
     */
    protected $name = false;

    /**
     * If the column has a validation error.
     *
     * @var bool
     */
    protected $isValidationError = false;

    /**
     * Stores any validation error, if any depending on the validation from the database.
     *
     * @var string
     */
    protected $validationErrorMessage = "";

    /**
     * @param array|null $columnDefinitions
     */
    public function __construct(array $columnDefinitions = null)
    {
        if (!is_null($columnDefinitions)) {
            $this->definition = $columnDefinitions;
        } else {
            $this->definition = array();
        }

        $this->name = $this->getValueFromDefinition('Field');
    }

    /**
     * @return bool|string
     */
    public function getType()
    {
        return $this->getValueFromDefinition('Type');
    }

    public function getSize()
    {
        // TODO: Implement getSize() method.
    }

    public function getDroDownValues()
    {
        // TODO: Implement getDroDownValues() method.
    }

    /**
     * @return bool|string
     */
    public function isNullNotAllowed()
    {
        return (strtoupper($this->getValueFromDefinition('Null')) == "YES") ? true : false;
    }

    /**
     * @return bool|string
     */
    public function isEmptyAllowed()
    {
        return !$this->isNullNotAllowed();
    }

    /**
     * @return bool|string
     */
    public function isPrimaryKey()
    {
        return (strtoupper($this->getValueFromDefinition('Key')) == "PRI") ? true : false;
    }

    /**
     * This function returns the value of the key from the column definitions.
     *
     * @param $key
     * @return bool|string
     */
    protected function getValueFromDefinition($key)
    {
        return (isset($this->definition) && !empty($this->definition[$key])) ? $this->definition[$key] : false;
    }

    /**
     * @ToDo: Add the possible validation here.
     *
     * @param string $value
     * @return bool
     */
    public function validate($value = "")
    {
        if (is_null($value) && $this->isEmptyAllowed()) {
            return true;
        }

        if ($this->isNullNotAllowed() && is_null($value)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isColumnVisible()
    {
        $keyValue = $this->getValueFromDefinition('Key');
        if($keyValue == "PRI") { //$keyValue == "MUL" ||
            return false;
        }

        return true;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * data_type:
     *
     * BIT[(length)]
     * | TINYINT[(length)] [UNSIGNED] [ZEROFILL]
     * | SMALLINT[(length)] [UNSIGNED] [ZEROFILL]
     * | MEDIUMINT[(length)] [UNSIGNED] [ZEROFILL]
     * | INT[(length)] [UNSIGNED] [ZEROFILL]
     * | INTEGER[(length)] [UNSIGNED] [ZEROFILL]
     * | BIGINT[(length)] [UNSIGNED] [ZEROFILL]
     * | REAL[(length,decimals)] [UNSIGNED] [ZEROFILL]
     * | DOUBLE[(length,decimals)] [UNSIGNED] [ZEROFILL]
     * | FLOAT[(length,decimals)] [UNSIGNED] [ZEROFILL]
     * | DECIMAL[(length[,decimals])] [UNSIGNED] [ZEROFILL]
     * | NUMERIC[(length[,decimals])] [UNSIGNED] [ZEROFILL]
     * -----------------------------------------------------
     * | DATE
     * | TIME
     * | TIMESTAMP
     * | DATETIME
     * | YEAR
     * ----------------------------------------------------------
     * | CHAR[(length)] [BINARY]
     *      [CHARACTER SET charset_name] [COLLATE collation_name]
     * | VARCHAR(length) [BINARY]
     *      [CHARACTER SET charset_name] [COLLATE collation_name]
     * -----------------------------------------------------
     * | BINARY[(length)]
     * | VARBINARY(length)
     * | TINYBLOB
     * | BLOB
     * | MEDIUMBLOB
     * | LONGBLOB
     * ----------------------------------------------------------
     * | TINYTEXT [BINARY]
     *      [CHARACTER SET charset_name] [COLLATE collation_name]
     * | TEXT [BINARY]
     *      [CHARACTER SET charset_name] [COLLATE collation_name]
     * | MEDIUMTEXT [BINARY]
     *      [CHARACTER SET charset_name] [COLLATE collation_name]
     * | LONGTEXT [BINARY]
     *      [CHARACTER SET charset_name] [COLLATE collation_name]
     * ----------------------------------------------------------
     * | ENUM(value1,value2,value3,...)
     *      [CHARACTER SET charset_name] [COLLATE collation_name]
     * | SET(value1,value2,value3,...)
     *      [CHARACTER SET charset_name] [COLLATE collation_name]
     * | spatial_type
     *
     * @return array
     */
    public function getHtmlElementType() {
        if(preg_match('/.*(enum|set).*/', $this->definition['Type'])) {
            $selectTag = array(
                'elTag' => 'select',
            );

            $option_string = "";

            if(preg_match('/.*enum.*/',$this->definition['Type'])) {
                $option_string = str_replace("')","",str_replace("enum('","",$this->definition['Type']));
            } else if(preg_match('/.*set.*/',$this->definition['Type'])) {
                $option_string = str_replace("')","",str_replace("set('","",$this->definition['Type']));
            }

            if(!empty($option_string)) {
                $optionArray = explode("','", $option_string);

                if(is_array($optionArray)) {
                    $selectTag['options'] = $optionArray;
                }
            }
            return $selectTag;

        } else if(preg_match('/.*(tinytext|text|mediumtext|longtext).*/', $this->definition['Type'])) {
            return array(
                'elTag' => 'textarea'
            );
        }
        else if(preg_match('/.*(date|datetime).*/', $this->definition['Type'])) {
            return array(
                'elTag' => 'input',
                'attributes' => array(
                    'type' => 'text',
                    'data-type' => 'date'
                )
            );
        }
        else
        {
            return array(
                'elTag' => 'input',
                'attributes' => array(
                    'type' => 'text'
                )
            );
        }
    }
}