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
     * The AddSlashes view helper provides methods for escaping 
     * slashes from a string
     *
     * @author Jon Matthews
     * @category MvcFramework
     * @package View
     * @subpackage Helper
     */
    class AddSlashes extends HelperAbstract
    {
        /**
         * Filters the string.
         *
         * @access public
         * @author  Jon Matthews
         * @param string $string
         * @return string
         */
        public function addSlashes($string)
        {
            $filter = new \MvcFramework\Filter\AddSlashes;

            return $filter->filter($string);
        }
    }
}