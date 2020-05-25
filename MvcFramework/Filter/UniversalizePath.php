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

namespace MvcFramework\Filter
{
    /**
     * The UniversalizePath class provides methods for filtering a path to be
     * universally consistent reglardless of OS.
     *
     * @category    MvcFramework
     * @package     Filter
     */
    class UniversalizePath extends \MvcFramework\Filter\FilterAbstract
    {
        /**
         * Filter method.
         *
         * @access public
         * @author Jon Matthews
         * @param string $value
         * @return string
         */
        public function filter($value)
        {
            return str_replace(array('/', '\\'), 
                                DIRECTORY_SEPARATOR, 
                                $value);
        }
    }
}