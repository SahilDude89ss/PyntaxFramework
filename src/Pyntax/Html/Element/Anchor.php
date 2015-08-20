<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 17/08/15
 * Time: 3:39 PM
 */

namespace Pyntax\Html\Element;


class Anchor extends Element
{

    public function __construct($link, $extraAttribute = array())
    {
        parent::__construct('a', true);
        $this->addAttribute('href', $link);
    }

}