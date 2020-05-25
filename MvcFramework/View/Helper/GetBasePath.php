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
     * The GetBasePath view helper provides methods for returning the application's
     * base path value
     *
     * @author Jon Matthews
     * @category MvcFramework
     * @package View
     * @subpackage Helper
     */
    class GetBasePath extends HelperAbstract
    {
        /**
         * Returns the base path value.
         *
         * @access public
         * @author Jon Matthews
         * @return string
         */
        public function getBasePath()
        {
            return \MvcFramework\Bootstrap\Core::getBootstrap()->getRequest()->getBasePath();
        }
    }
}