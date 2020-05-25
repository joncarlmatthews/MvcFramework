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

namespace MvcFramework\Acl\Resource
{
    /**
     * The ResourceInterface interface provides methods a consistent interface
     * for Acl Resource classes.
     *
     * @category    MvcFramework
     * @package     Acl
     * @subpackage  Resource
     */
    interface ResourceInterface
    {
        /**
         * Class constructor. Sets the resourceID.
         *
         * @access public
         * @author  Jon Matthews
         * @return mixed
         */
        public function __construct($resourceID);

        /**
         * Returns the instance's resource ID.
         *
         * @access public
         * @author  Jon Matthews
         * @return mixed
         */
        public function getResourceID();
    }
}