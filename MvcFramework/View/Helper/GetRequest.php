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
     * The GetRequest view helper provides methods for returning the request 
     * object.
     *
     * @author Jon Matthews
     * @category MvcFramework
     * @package View
     * @subpackage Helper
     */
    class GetRequest extends HelperAbstract
    {
        /**
         * Return the request object.
         *
         * @access public
         * @author Jon Matthews
         * @return Request
         */
        public function getRequest()
        {
            return \MvcFramework\Bootstrap\Core::getBootstrap()->getRequest();
        }
    }
}