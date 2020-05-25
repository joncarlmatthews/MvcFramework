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

namespace MvcFramework\Bootstrap
{
    /**
     * The BootstrapInterface interface provides methods a consistent interface
     * for Application Bootstrap classes.
     *
     * @category    MvcFramework
     * @package     Bootstrap
     */
    interface BootstrapInterface
    {
        public function init();
        public function run();
    }
}