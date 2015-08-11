<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 17/07/15
 * Time: 3:42 PM
 */

namespace Pyntax\Html\Element\Attribute;


class Attribute
{
    /**
     * @var string
     */
    protected $name = "";

    /**
     * @var string
     */
    protected $value = "";

    /**
     * @var bool
     */
    protected $isAttributeArrayable = false;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}