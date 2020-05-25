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
     * The Int class provides methods for filtering an integer value using 
     * type casting.
     *
     * @category    MvcFramework
     * @package     Filter
     * @subpackage  Int
     */
    class Integer extends \MvcFramework\Filter\FilterAbstract
    {
        /**
         * Filter method.
         *
         * @access public
         * @author Jon Matthews
         * @param int $value
         * @return int
         */
        public function filter($value)
        {
            return (int)$value;
        }
    }
}