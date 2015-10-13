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

namespace Pyntax\Html;

use Pyntax\Config\Config;
use Pyntax\Html\Element\ElementFactory;
use Pyntax\Html\Form\FormFactory;
use Pyntax\Html\Table\TableFactory;

/**
 * Class HtmlFactoryAbstract
 * @package Pyntax\Html
 */
abstract class HtmlFactoryAbstract implements HtmlFactoryInterface
{
    const FilePlacementOption_Header = 'header';
    const FilePlacementOption_Footer = 'footer';

    const FileTypeOption_CSS = 'css';
    const FileTypeOption_JS = 'js';

    /**
     * @var array
     */
    protected $_html_config = array();

    /**
     * @var array
     */
    protected $_files = array(
        'header' => array(
            'css' => array(),
            'js' => array()
        ),
        'footer' => array(
            'css' => array(),
            'js' => array()
        )
    );

    /**
     * @var null
     */
    protected $_form_factory = null;

    /**
     * @var null
     */
    protected $_table_factory = null;

    /**
     * @var null
     */
    protected $_element_factory = null;

    protected function loadConfig() {
//        $_config = Config::readConfig('html');
        $_config = new Config('html', 'html.config.php');
        $this->_html_config = $_config;
    }

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
    protected function setupElementFactory() {
        if(!$this->_element_factory) {
            $this->_element_factory = new ElementFactory();
        }

        return true;
    }


    /**
     * @param $path
     */
    public function addJsFileToFooter($path) {
        $this->addFooterFile($path, self::FilePlacementOption_Footer);
    }

    /**
     * @param $path
     */
    public function addJsFileToHeader($path) {
        $this->addHeaderFile($path, self::FilePlacementOption_Header);
    }

    /**
     * @param $path
     */
    public function addCssFileToFooter($path) {
        $this->addFooterFile($path, self::FileTypeOption_CSS);
    }

    /**
     * @param $path
     */
    public function addCssFileToHeader($path) {
        $this->addHeaderFile($path, self::FileTypeOption_CSS);
    }

    /**
     * @param $path
     * @param string $type
     */
    public function addHeaderFile($path, $type = self::FileTypeOption_JS) {
        $this->addFile($path, $type, self::FilePlacementOption_Header);
    }

    /**
     * @param $path
     * @param string $type
     */
    public function addFooterFile($path, $type = self::FileTypeOption_JS) {
        $this->addFile($path, $type, self::FilePlacementOption_Footer);
    }

    /**
     * @param $path
     * @param string $type
     * @param string $printPlace
     */
    public function addFile($path, $type = self::FileTypeOption_JS, $printPlace = self::FilePlacementOption_Header) {
        if(is_array($path)) {
            foreach($path as $_file) {
                $this->addFile($_file, $type, $printPlace);
            }
        } else if(is_string($path)) {
            $this->_files[$printPlace][$type][] = $path;
        }
    }

    /**
     * @param $path
     * @param string $type
     * @param string $printPlace
     */
    public function removeFile($path, $type = self::FileTypeOption_JS, $printPlace = self::FilePlacementOption_Header) {
        if(is_array($path)) {
            foreach($path as $_file) {
                $this->removeFile($_file, $type, $printPlace);
            }
        } else if(is_string($path)) {
            unset($this->_files[$printPlace][$type][$path]);
        }
    }

    /**
     * @param string $printPlace
     */
    public function printJSFiles($printPlace = self::FilePlacementOption_Header)
    {
        $this->loadFilesFromConfig($printPlace);
        foreach($this->_files[$printPlace][self::FileTypeOption_JS] as $_file) {
            echo $this->_element_factory->generateElement('script', array(
                'type' => 'text/javascript',
                'src' => $_file
            ), "")."\n";
        }
    }

    /**
     * @param string $printPlace
     */
    public function printCSSFiles($printPlace = self::FilePlacementOption_Header)
    {
        $this->loadFilesFromConfig(self::FileTypeOption_CSS, $printPlace);
        foreach($this->_files[$printPlace][self::FileTypeOption_CSS] as $_file) {
            echo $this->_element_factory->generateElement('link', array(
                'rel' => 'stylesheet',
                'href' => $_file
            ), "")."\n";
        }
    }

    /**
     * @param string $fileType
     * @param string $printPlace
     */
    public function loadFilesFromConfig($fileType = self::FileTypeOption_CSS ,$printPlace = self::FilePlacementOption_Header) {
        $_html_config = $this->_html_config->readConfig();
        if(isset($_html_config[$fileType][$printPlace])) {
            if(isset($this->_files[$printPlace][$fileType])) {
                $this->_files[$printPlace][$fileType] = array_merge($this->_files[$printPlace][$fileType], $_html_config[$fileType][$printPlace]);
            }
        }
    }

    /**
     * @param $dirPath
     * @param string $type
     * @param string $printPlace
     *
     * @return array
     */
    public function scanDirForFiles($dirPath, $type = self::FileTypeOption_JS, $printPlace = self::FilePlacementOption_Header)
    {
        if(is_dir($dirPath))
        {
            foreach(scandir($dirPath) as $_file)
            {
                if(is_dir($dirPath.DIRECTORY_SEPARATOR.$_file) && ($_file != "." && $_file != "..")) {
                    $this->scanDirForFiles($dirPath.DIRECTORY_SEPARATOR.$_file, $type, $printPlace);
                } else {
                    if($_file != "." && $_file != "..") {
                        $this->addFile($dirPath.DIRECTORY_SEPARATOR.$_file, $type, $printPlace);
                    }
                }

            }
        }
    }
}