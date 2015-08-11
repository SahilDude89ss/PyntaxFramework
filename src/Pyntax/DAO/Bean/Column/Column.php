<?php

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

}