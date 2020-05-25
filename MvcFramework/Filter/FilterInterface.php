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
     * The FilterInterface interface provides methods a consistent interface
     * for Filter classes.
     *
     * @category    MvcFramework
     * @package     Filter
     * @subpackage  FilterInterface
     */
    interface FilterInterface
    {
        public function __construct(array $options = array());
        
        public function filter($value);    
    }
}