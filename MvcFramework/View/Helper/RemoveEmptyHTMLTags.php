<?php

/**
 * MvcFramework
 *
 * @link        https://github.com/joncarlmatthews/MvcFramework for the canonical source repository
 * @copyright   
 * @link        Coded to the Zend Framework Coding Standard for PHP 
 *              http://framework.zend.com/manual/1.12/en/coding-standard.html
 * 
 * File format: UNIX
 * File encoding: UTF8
 * File indentation: Spaces (4). No tabs
 *
 */

namespace MvcFramework\View\Helper
{
    /**
     * The RemoveEmptyHTMLTags view helper provides methods for removing empty
     * HTML tags
     *
     * @author Jon Matthews
     * @category MvcFramework
     * @package View
     * @subpackage Helper
     */
    class RemoveEmptyHTMLTags extends HelperAbstract
    {
        /**
         * Escapes the string.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $string
         * @return string
         */
        public function removeEmptyHTMLTags($string)
        {
            $filter = new \MvcFramework\Filter\RemoveEmptyHTMLTags;

            return $filter->filter($string);
        }
    }
}