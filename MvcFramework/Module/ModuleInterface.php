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

namespace MvcFramework\Module
{
    /**
     * The ModuleInterface interface provides a consistent interface for 
     * extension.
     *
     * @author Jon Matthews
     * @category MvcFramework
     * @package Mvc_Module
     * @subpackage ModuleInterface
     */
    interface ModuleInterface 
    {
        public function __construct($name);

        public function getName();
    }
}