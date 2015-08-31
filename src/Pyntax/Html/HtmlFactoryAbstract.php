<?php
/**
 * Created by PhpStorm.
 * User: ssharma
 * Date: 31/08/15
 * Time: 4:31 PM
 */

namespace Pyntax\Html;
use Pyntax\Html\Form\FormFactory;
use Pyntax\Html\Table\TableFactory;

/**
 * Class HtmlFactoryAbstract
 * @package Pyntax\Html
 */
abstract class HtmlFactoryAbstract implements HtmlFactoryInterface
{
    /**
     * @var null
     */
    protected $_form_factory = null;

    /**
     * @var null
     */
    protected $_html_factory = null;

    /**
     * @var null
     */
    protected $_table_factory = null;

    /**
     * @return bool
     */
    protected function setUpFormFactory() {
        if(!$this->_form_factory) {
            $this->_form_factory = new FormFactory();
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function setUpTableFactory() {
        if(!$this->_table_factory) {
            $this->_table_factory = new TableFactory();
        }

        return true;
    }
    /**
     * @return bool
     */
    protected function setUpHtmlFactory() {
        if(!$this->_html_factory) {
            $this->_html_factory = new HtmlFactory();
        }

        return true;
    }
}