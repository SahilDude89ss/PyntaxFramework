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

namespace Pyntax\Html\Form;
use Pyntax\DAO\Bean\BeanInterface;

/**
 * Interface FormFactoryInterface
 * @package Pyntax\Form
 */
interface FormFactoryInterface
{
    /**
     * @param BeanInterface $bean
     * @param bool|false $returnString
     *
     * @return mixed
     */
    public function generateForm(BeanInterface $bean, $returnString = false);

    /**
     * @param BeanInterface $bean
     *
     * @return mixed
     */
    public function generateSubmitButton(BeanInterface $bean);

    /**
     * @param array $_columns_to_be_displayed
     * @param BeanInterface $bean
     *
     * @return mixed
     */
    public function generateFormColumn(array $_columns_to_be_displayed, BeanInterface $bean);

    /**
     * @param array $config
     * @param $elementName
     * @param $beanName
     * @param string $customKeyName
     *
     * @return bool|mixed
     */
    public function getConfigForElement(array $config, $elementName, $beanName, $customKeyName = 'beans');

    /**
     * @param array $config
     *
     * @return mixed
     */
    public function generateElementWithArrayConfig(array $config = array());

    /**
     * @return mixed
     */
    public function saveBean();
}